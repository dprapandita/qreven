<?php

namespace App\Http\Controllers;

use App\Enums\BoothBookingStatus;
use App\Enums\BoothStatus;
use App\Enums\EventStatus;
use App\Models\Booth;
use App\Models\BoothBooking;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BoothBookingController extends Controller
{
    public function index(Event $event): Collection
    {
        $this->authorizeEventAccess($event, 'viewOrders');

        return BoothBooking::query()
            ->whereHas('booth', fn ($query) => $query->where('event_id', $event->id))
            ->with(['user', 'booth'])
            ->latest()
            ->get();
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

    public function approve(Event $event, string $booking): JsonResponse
    {
        $this->authorizeEventAccess($event, 'update');

        DB::transaction(function () use ($event, $booking): void {
            $booking = $this->lockBooking($event, $booking);

            abort_if($booking->status !== BoothBookingStatus::Pending, 422, 'Pemesanan tidak dapat disetujui.');

            $booth = Booth::query()->whereKey($booking->booth_id)->lockForUpdate()->firstOrFail();

            abort_if($booth->status !== BoothStatus::Available, 422, 'Booth tidak tersedia.');

            $otherApproved = BoothBooking::query()
                ->where('booth_id', $booth->id)
                ->where('status', BoothBookingStatus::Approved->value)
                ->exists();

            abort_if($otherApproved, 422, 'Booth sudah memiliki pemesanan yang disetujui.');

            $booking->update([
                'status' => BoothBookingStatus::Approved->value,
                'approved_at' => now(),
            ]);

            $booth->update(['status' => BoothStatus::Booked->value]);
        });

        return response()->json([
            'message' => 'Pemesanan booth disetujui',
            'data' => BoothBooking::query()->whereKey($booking)->firstOrFail(),
        ]);
    }

    public function reject(Event $event, string $booking): JsonResponse
    {
        $this->authorizeEventAccess($event, 'update');

        DB::transaction(function () use ($event, $booking): void {
            $booking = $this->lockBooking($event, $booking);

            abort_if($booking->status !== BoothBookingStatus::Pending, 422, 'Pemesanan tidak dapat ditolak.');

            $booking->update(['status' => BoothBookingStatus::Rejected->value]);
        });

        return response()->json([
            'message' => 'Pemesanan booth ditolak',
            'data' => BoothBooking::query()->whereKey($booking)->firstOrFail(),
        ]);
    }

    public function cancelByManager(Event $event, string $booking): JsonResponse
    {
        $this->authorizeEventAccess($event, 'update');

        return $this->cancelBooking($event, $booking);
    }

    public function cancelOwn(Request $request, string $booking): JsonResponse
    {
        $event = $this->resolveEvent($request);

        $model = BoothBooking::query()
            ->whereKey($booking)
            ->whereHas('booth', fn ($query) => $query->where('event_id', $event->id))
            ->firstOrFail();

        Gate::authorize('cancel', $model);

        return $this->cancelBooking($event, $booking);
    }

    private function cancelBooking(Event $event, string $booking): JsonResponse
    {
        DB::transaction(function () use ($event, $booking): void {
            $booking = $this->lockBooking($event, $booking);

            abort_unless(in_array($booking->status, [
                BoothBookingStatus::Pending,
                BoothBookingStatus::Approved,
            ], true), 422, 'Pemesanan tidak dapat dibatalkan.');

            $wasApproved = $booking->status === BoothBookingStatus::Approved;

            $booking->update([
                'status' => BoothBookingStatus::Cancelled->value,
                'cancelled_at' => now(),
            ]);

            if (! $wasApproved) {
                return;
            }

            $booth = Booth::query()->whereKey($booking->booth_id)->lockForUpdate()->firstOrFail();

            $otherApproved = BoothBooking::query()
                ->where('booth_id', $booth->id)
                ->where('status', BoothBookingStatus::Approved->value)
                ->exists();

            if (! $otherApproved) {
                $booth->update(['status' => BoothStatus::Available->value]);
            }
        });

        return response()->json([
            'message' => 'Pemesanan booth dibatalkan',
        ]);
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
