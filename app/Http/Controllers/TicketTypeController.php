<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index()
    {
        return TicketType::with('event')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'nama_tiket' => 'required',
            'harga' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1'
        ]);

        return TicketType::create($data);
    }

    public function update(Request $request, $id)
    {
        $ticket = TicketType::findOrFail($id);
        $ticket->update($request->only([
            'nama_tiket', 'harga', 'kuota'
        ]));

        return $ticket;
    }

    public function destroy($id)
    {
        TicketType::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Jenis tiket berhasil dihapus'
        ]);
    }
}