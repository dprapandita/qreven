<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'kode_tiket' => 'required'
        ]);

        $ticket = Ticket::where(
            'kode_tiket',
            $request->kode_tiket
        )->first();

        if (!$ticket) {
            return response()->json([
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        if ($ticket->checked_in_at) {
            return response()->json([
                'message' => 'Tiket sudah digunakan'
            ], 422);
        }

        $ticket->update([
            'checked_in_at' => now(),
            'checked_in_by' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Check-in berhasil',
            'ticket' => $ticket
        ]);
    }
}