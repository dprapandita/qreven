<?php

namespace App\Http\Controllers;

use App\Enums\BoothBookingStatus;
use App\Enums\BoothStatus;
use App\Enums\EventStatus;
use App\Models\Booth;
use App\Models\BoothBooking;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BoothBookingController extends Controller
{
    public function index(Request $request, Event $event): View|JsonResponse
    {
        $this->authorizeEventAccess($event, 'orders');

        $bookings = BoothBooking::query()
            ->whereHas('booth', fn ($query) => $query->where('event_id', $event->id))
            ->with(['user', 'booth.event'])
            ->latest()
            ->get();

        if ($request->expectsJson()) {
            return response()->json($bookings);
        }

        return view('booth-bookings.index', compact('bookings', 'event'));
    }

    public function store(Request $request): JsonResponse
    {
        $event = $this->resolveEvent($request);

        abort_unless($event->status === EventStatus::Published, 404);

        $data = $request->validate([
            'booth_id' => ['required', 'uuid'],
            'catatan' => ['nullable', 'string'],
        ]);

        $booking = DB::transaction(function () use ($event, $data): BoothBooking {
            $booth = $event->booths()
                ->whereKey($data['booth_id'])
                ->lockForUpdate()
                ->first();

            abort_if($booth === null, 422, 'Booth tidak ditemukan untuk event ini.');
            abort_if($booth->status !== BoothStatus::Available, 422, 'Booth tidak tersedia.');

            $alreadyBooked = $booth->bookings()
                ->where('user_id', auth()->id())
                ->whereIn('status', [
                    BoothBookingStatus::Pending->value,
                    BoothBookingStatus::Approved->value,
                ])
                ->exists();

            abort_if($alreadyBooked, 422, 'Anda sudah mengajukan pemesanan booth ini.');

            return BoothBooking::create([
                'booth_id' => $booth->id,
                'user_id' => auth()->id(),
                'status' => BoothBookingStatus::Pending->value,
                'catatan' => $data['catatan'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Pemesanan booth diajukan',
            'data' => $booking,
        ], 201);
    }

    public function update(Request $request): RedirectResponse
    {
        $booking = $request->route('booking');

        $model = BoothBooking::query()->with('booth.event')->findOrFail($booking);
        $event = $model->booth->event;

        abort_if($event === null, 404);

        $this->authorizeEventAccess($event, 'manage');

        $status = $request->validate([
            'status' => ['required', 'in:disetujui,ditolak'],
        ])['status'];

        if ($status === BoothBookingStatus::Approved->value) {
            $this->approveBooking($event, $booking);
            $message = 'Pemesanan booth disetujui';
        } else {
            $this->rejectBooking($event, $booking);
            $message = 'Pemesanan booth ditolak';
        }

        return redirect()->route('booth-bookings.index', $event)->with('success', $message);
    }

    public function approve(Event $event, string $booking): JsonResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $this->approveBooking($event, $booking);

        return response()->json([
            'message' => 'Pemesanan booth disetujui',
            'data' => BoothBooking::query()->whereKey($booking)->firstOrFail(),
        ]);
    }

    public function reject(Event $event, string $booking): JsonResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $this->rejectBooking($event, $booking);

        return response()->json([
            'message' => 'Pemesanan booth ditolak',
            'data' => BoothBooking::query()->whereKey($booking)->firstOrFail(),
        ]);
    }

    public function cancelByManager(Event $event, string $booking): JsonResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $this->cancelBooking($event, $booking);

        return response()->json([
            'message' => 'Pemesanan booth dibatalkan',
        ]);
    }

    public function cancelOwn(Request $request): JsonResponse
    {
        $event = $this->resolveEvent($request);
        $booking = $request->route('booking');

        $model = BoothBooking::query()
            ->whereKey($booking)
            ->whereHas('booth', fn ($query) => $query->where('event_id', $event->id))
            ->firstOrFail();

        abort_unless(
            $model->user_id === auth()->id() || $event->canManageOrdersBy(auth()->user()),
            403,
        );

        $this->cancelBooking($event, $booking);

        return response()->json([
            'message' => 'Pemesanan booth dibatalkan',
        ]);
    }

    private function approveBooking(Event $event, string $booking): void
    {
        DB::transaction(function () use ($event, $booking): void {
            $model = $this->lockBooking($event, $booking);

            abort_if($model->status !== BoothBookingStatus::Pending, 422, 'Pemesanan tidak dapat disetujui.');

            $booth = Booth::query()->whereKey($model->booth_id)->lockForUpdate()->firstOrFail();

            abort_if($booth->status !== BoothStatus::Available, 422, 'Booth tidak tersedia.');

            $otherApproved = BoothBooking::query()
                ->where('booth_id', $booth->id)
                ->where('status', BoothBookingStatus::Approved->value)
                ->exists();

            abort_if($otherApproved, 422, 'Booth sudah memiliki pemesanan yang disetujui.');

            $model->update([
                'status' => BoothBookingStatus::Approved->value,
                'approved_at' => now(),
            ]);

            $booth->update(['status' => BoothStatus::Booked->value]);
        });
    }

    private function rejectBooking(Event $event, string $booking): void
    {
        DB::transaction(function () use ($event, $booking): void {
            $model = $this->lockBooking($event, $booking);

            abort_if($model->status !== BoothBookingStatus::Pending, 422, 'Pemesanan tidak dapat ditolak.');

            $model->update(['status' => BoothBookingStatus::Rejected->value]);
        });
    }

    private function cancelBooking(Event $event, string $booking): void
    {
        DB::transaction(function () use ($event, $booking): void {
            $model = $this->lockBooking($event, $booking);

            abort_unless(in_array($model->status, [
                BoothBookingStatus::Pending,
                BoothBookingStatus::Approved,
            ], true), 422, 'Pemesanan tidak dapat dibatalkan.');

            $wasApproved = $model->status === BoothBookingStatus::Approved;

            $model->update([
                'status' => BoothBookingStatus::Cancelled->value,
                'cancelled_at' => now(),
            ]);

            if (! $wasApproved) {
                return;
            }

            $booth = Booth::query()->whereKey($model->booth_id)->lockForUpdate()->firstOrFail();

            $otherApproved = BoothBooking::query()
                ->where('booth_id', $booth->id)
                ->where('status', BoothBookingStatus::Approved->value)
                ->exists();

            if (! $otherApproved) {
                $booth->update(['status' => BoothStatus::Available->value]);
            }
        });
    }

    private function lockBooking(Event $event, string $booking): BoothBooking
    {
        return BoothBooking::query()
            ->whereKey($booking)
            ->whereHas('booth', fn ($query) => $query->where('event_id', $event->id))
            ->lockForUpdate()
            ->firstOrFail();
    }
}
