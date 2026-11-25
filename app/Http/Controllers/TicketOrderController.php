<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Enums\TicketOrderStatus;
use App\Enums\TicketStatus;
use App\Jobs\ExpireTicketOrder;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketOrder;
use App\Models\TicketOrderItem;
use App\Support\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class TicketOrderController extends Controller
{
    public function index(Event $event): Collection
    {
        $this->authorizeEventAccess($event, 'viewOrders');

        return $event->ticketOrders()
            ->with(['items', 'tickets'])
            ->latest()
            ->get();
    }

    public function store(Request $request): JsonResponse
    {
        $event = $this->resolveEvent($request);

        abort_unless($event->status === EventStatus::Published, 404);

        $data = $request->validate([
            'ticket_type_id' => ['required', 'uuid'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($event, $data): TicketOrder {
            $type = $event->ticketTypes()
                ->whereKey($data['ticket_type_id'])
                ->lockForUpdate()
                ->first();

            abort_if($type === null, 422, 'Jenis tiket tidak ditemukan untuk event ini.');
            abort_if($data['jumlah'] > $type->kuota, 422, 'Kuota tiket tidak mencukupi.');

            $unitPrice = Money::fromCents(Money::toCents((string) $type->harga));
            $total = Money::multiply((string) $type->harga, $data['jumlah']);
            $isFree = Money::toCents($total) === 0;

            $order = TicketOrder::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'total_harga' => $total,
                'status_pembayaran' => $isFree
                    ? TicketOrderStatus::Paid->value
                    : TicketOrderStatus::Pending->value,
                'paid_at' => $isFree ? now() : null,
                'expires_at' => $isFree
                    ? null
                    : now()->addMinutes(config('events.order_expiry_minutes')),
            ]);

            TicketOrderItem::create([
                'ticket_order_id' => $order->id,
                'ticket_type_id' => $type->id,
                'quantity' => $data['jumlah'],
                'unit_price' => $unitPrice,
                'subtotal' => $total,
            ]);

            for ($i = 0; $i < $data['jumlah']; $i++) {
                Ticket::create([
                    'ticket_order_id' => $order->id,
                    'ticket_type_id' => $type->id,
                    'kode_tiket' => Str::uuid(),
                    'status' => TicketStatus::Valid->value,
                ]);
            }

            $type->decrement('kuota', $data['jumlah']);

            return $order;
        });

        if ($order->isPending()) {
            ExpireTicketOrder::dispatch($order)->afterCommit();
        }

        return response()->json([
            'message' => 'Pemesanan berhasil dibuat',
            'data' => $order->load('items', 'tickets'),
        ], 201);
    }

    public function pay(Event $event, TicketOrder $order): JsonResponse
    {
        $this->authorizeEventAccess($event, 'manageOrders');

        abort_unless($order->event_id === $event->id, 404);

        $paid = DB::transaction(function () use ($order): bool {
            $order = TicketOrder::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($order->isPending() && $order->expires_at !== null && $order->expires_at->isPast()) {
                $order->expire();

                return false;
            }

            return $order->markPaid();
        });

        if (! $paid) {
            return response()->json([
                'message' => 'Pesanan tidak dapat dibayar.',
            ], 422);
        }

        return response()->json([
            'message' => 'Pembayaran pesanan dikonfirmasi',
            'data' => $order->refresh(),
        ]);
    }

    public function cancel(Event $event, TicketOrder $order): JsonResponse
    {
        $this->authorizeEventAccess($event, 'manageOrders');

        abort_unless($order->event_id === $event->id, 404);

        return $this->cancelOrder($order);
    }

    public function cancelOwn(Request $request, string $order): JsonResponse
    {
        $event = $this->resolveEvent($request);

        $order = TicketOrder::query()
            ->whereKey($order)
            ->where('event_id', $event->id)
            ->firstOrFail();

        Gate::authorize('cancel', $order);

        return $this->cancelOrder($order);
    }

    private function cancelOrder(TicketOrder $order): JsonResponse
    {
        $cancelled = DB::transaction(function () use ($order): bool {
            $order = TicketOrder::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (! $order->isPending()) {
                return false;
            }

            return $order->cancelPending();
        });

        if (! $cancelled) {
            return response()->json([
                'message' => 'Hanya pesanan yang belum dibayar yang dapat dibatalkan.',
            ], 422);
        }

        return response()->json([
            'message' => 'Pesanan dibatalkan',
        ]);
    }
}
