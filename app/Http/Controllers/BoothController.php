<?php

namespace App\Http\Controllers;

use App\Enums\BoothStatus;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoothController extends Controller
{
    public function index(Request $request, Event $event): View|JsonResponse
    {
        $this->authorizeEventAccess($event, 'view');

        $booths = $event->booths()->orderBy('nama_booth')->get();

        if ($request->expectsJson()) {
            return response()->json($booths);
        }

        return view('booths.index', compact('event', 'booths'));
    }

    public function create(Event $event): View
    {
        $this->authorizeEventAccess($event, 'manage');

        return view('booths.create', compact('event'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $data = $request->validate([
            'nama_booth' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        $event->booths()->create([
            'nama_booth' => $data['nama_booth'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'harga' => $data['harga'],
            'status' => BoothStatus::Available->value,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Booth berhasil ditambahkan'], 201);
        }

        return redirect()->route('booths.index', $event)
            ->with('success', 'Booth berhasil ditambahkan');
    }

    public function edit(Event $event, string $booth): View
    {
        $this->authorizeEventAccess($event, 'manage');

        $booth = $event->booths()->findOrFail($booth);

        return view('booths.edit', compact('event', 'booth'));
    }

    public function update(Request $request, Event $event, string $booth): RedirectResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $booth = $event->booths()->findOrFail($booth);

        $data = $request->validate([
            'nama_booth' => ['sometimes', 'required', 'string', 'max:255'],
            'deskripsi' => ['sometimes', 'nullable', 'string'],
            'harga' => ['sometimes', 'required', 'numeric', 'min:0'],
        ]);

        $booth->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Booth berhasil diperbarui']);
        }

        return redirect()->route('booths.index', $event)
            ->with('success', 'Booth berhasil diperbarui');
    }

    public function destroy(Request $request, Event $event, string $booth): RedirectResponse|JsonResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $booth = $event->booths()->findOrFail($booth);

        abort_if($booth->bookings()->exists(), 422, 'Booth memiliki riwayat pemesanan.');

        $booth->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Booth berhasil dihapus']);
        }

        return redirect()->route('booths.index', $event)
            ->with('success', 'Booth berhasil dihapus');
    }
}
