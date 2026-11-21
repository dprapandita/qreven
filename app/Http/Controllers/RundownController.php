<?php

namespace App\Http\Controllers;

use App\Models\Rundown;
use Illuminate\Http\Request;

class RundownController extends Controller
{
    public function index()
    {
        return Rundown::orderBy('waktu_mulai')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'nama_kegiatan' => 'required',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date',
            'deskripsi' => 'nullable'
        ]);

        return Rundown::create($data);
    }

    public function update(Request $request, $id)
    {
        $rundown = Rundown::findOrFail($id);
        $rundown->update($request->all());

        return $rundown;
    }

    public function destroy($id)
    {
        Rundown::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Rundown berhasil dihapus'
        ]);
    }
}