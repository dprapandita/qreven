<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\BoothBooking;
use Illuminate\Http\Request;

class BoothBookingController extends Controller
{
    public function index()
    {
        return BoothBooking::with(['user', 'booth'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'booth_id' => 'required|exists:booths,id',
            'catatan' => 'nullable'
        ]);

        $booth = Booth::findOrFail($data['booth_id']);

        if ($booth->status != 'tersedia') {
            return response()->json([
                'message' => 'Booth tidak tersedia'
            ], 422);
        }

        return BoothBooking::create([
            'booth_id' => $booth->id,
            'user_id' => auth()->id(),
            'status' => 'menunggu',
            'catatan' => $data['catatan'] ?? null
        ]);
    }

    // Admin menyetujui atau menolak pemesanan
    public function update(Request $request, $id)
    {
        $booking = BoothBooking::findOrFail($id);

        $data = $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        $booking->update($data);

        if ($data['status'] == 'disetujui') {
            $booking->booth->update([
                'status' => 'dipesan'
            ]);
        }

        return $booking;
    }
}