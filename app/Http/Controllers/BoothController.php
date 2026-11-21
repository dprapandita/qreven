<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use Illuminate\Http\Request;

class BoothController extends Controller
{
    public function index()
    {
        return Booth::with('event')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'nama_booth' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric|min:0'
        ]);

        $data['status'] = 'tersedia';

        return Booth::create($data);
    }

    public function update(Request $request, $id)
    {
        $booth = Booth::findOrFail($id);

        $booth->update($request->only([
            'nama_booth', 'deskripsi', 'harga', 'status'
        ]));

        return $booth;
    }

    public function destroy($id)
    {
        Booth::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Booth berhasil dihapus'
        ]);
    }
}