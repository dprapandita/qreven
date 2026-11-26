<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Enums\TicketOrderStatus;
use App\Enums\TicketStatus;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckInController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $events = Event::query()
            ->where('user_id', $user->id)
            ->orWhereHas('members', fn ($query) => $query->whereKey($user->id))
            ->get()
            ->filter(fn (Event $event) => $event->canCheckInBy($user))
            ->values();

        return view('checkin.index', compact('events'));
    }

    public function verify(Request $request): JsonResponse
    {
        $event = $this->resolveEvent($request);

        abort_unless($event->status === EventStatus::Published, 404);

        $this->authorizeEventAccess($event, 'checkin');

        $data = $request->validate([
            'kode_tiket' => ['required', 'string'],
        ]);

        $ticket = $this->performCheckIn($event, $data['kode_tiket']);

        return response()->json([
            'message' => 'Check-in berhasil',
            'ticket' => $ticket,
        ]);
    }

    public function verifyForm(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'event_id' => ['required', 'uuid', 'exists:events,id'],
            'kode_tiket' => ['required', 'string'],
        ]);

        $event = Event::query()->findOrFail($data['event_id']);

        $this->authorizeEventAccess($event, 'checkin');

        try {
            $ticket = $this->performCheckIn($event, $data['kode_tiket']);
        } catch (HttpException $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }

        return back()->with('success', 'Check-in berhasil.')->with('ticket', $ticket);
    }

    private function performCheckIn(Event $event, string $code): Ticket
    {
        return DB::transaction(function () use ($event, $code): Ticket {
            $ticket = Ticket::query()
                ->where('kode_tiket', $code)
                ->whereHas('ticketOrder', fn ($query) => $query->where('event_id', $event->id))
                ->lockForUpdate()
                ->first();

            if ($ticket === null) {
                abort(404, 'Tiket tidak ditemukan untuk event ini.');
            }

            $order = $ticket->ticketOrder;

            abort_if($order->status_pembayaran !== TicketOrderStatus::Paid, 422, 'Tiket belum dibayar.');
            abort_if($order->inventory_released_at !== null, 422, 'Pesanan tiket sudah dibatalkan.');
            abort_if($ticket->status === TicketStatus::Cancelled, 422, 'Tiket dibatalkan.');

            $updated = Ticket::query()
                ->whereKey($ticket->id)
                ->whereNull('checked_in_at')
                ->where('status', '!=', TicketStatus::Cancelled->value)
                ->update([
                    'status' => TicketStatus::CheckedIn->value,
                    'checked_in_at' => now(),
                    'checked_in_by' => auth()->id(),
                ]);

            if ($updated === 0) {
                abort(422, 'Tiket sudah digunakan.');
            }

            return $ticket->refresh();
        });
    }
}
