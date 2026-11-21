<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Menampilkan semua event
    public function index()
    {
        $events = Event::all();

        return view('events.index', compact('events'));
    }

    // Menampilkan detail event
    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', compact('event'));
    }

    // Menyimpan event baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_event' => 'required',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        $data['user_id'] = auth()->id();

        Event::create($data);

        return redirect()->back()
            ->with('success', 'Event berhasil ditambahkan');
    }

    // Mengubah event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $event->update($request->validate([
            'nama_event' => 'required',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]));

        return redirect()->back()
            ->with('success', 'Event berhasil diperbarui');
    }

    // Menghapus event
    public function destroy($id)
    {
        Event::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Event berhasil dihapus');
    }
}