<?php

namespace App\Http\Controllers;

use App\Models\TicketOrder;
use App\Models\TicketType;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TicketOrderController extends Controller
{
    public function index()
    {
        return TicketOrder::with('tickets')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $order = DB::transaction(function () use ($data) {

            $type = TicketType::where('id', $data['ticket_type_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($data['jumlah'] > $type->kuota) {
                abort(422, 'Kuota tiket tidak mencukupi');
            }

            $order = TicketOrder::create([
                'user_id' => auth()->id(),
                'event_id' => $type->event_id,
                'total_harga' => $type->harga * $data['jumlah'],
                'status_pembayaran' => 'pending'
            ]);

            for ($i = 0; $i < $data['jumlah']; $i++) {
                Ticket::create([
                    'ticket_order_id' => $order->id,
                    'ticket_type_id' => $type->id,
                    'kode_tiket' => Str::uuid(),
                    'status' => 'belum_checkin'
                ]);
            }

            $type->decrement('kuota', $data['jumlah']);

            return $order;
        });

        return response()->json([
            'message' => 'Pemesanan berhasil dibuat',
            'data' => $order
        ]);
    }
}