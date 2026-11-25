<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Enums\TicketOrderStatus;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{
    public function verify(Request $request): JsonResponse
    {
        $event = $this->resolveEvent($request);

        abort_unless($event->status === EventStatus::Published, 404);

        $this->authorizeEventAccess($event, 'checkIn');

        $data = $request->validate([
            'kode_tiket' => ['required', 'string'],
        ]);

        $ticket = DB::transaction(function () use ($event, $data): Ticket {
            $ticket = Ticket::query()
                ->where('kode_tiket', $data['kode_tiket'])
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

        return response()->json([
            'message' => 'Check-in berhasil',
            'ticket' => $ticket,
        ]);
    }
}
