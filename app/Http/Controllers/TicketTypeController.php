<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketTypeController extends Controller
{
    public function index(Request $request, Event $event): View|JsonResponse
    {
        $this->authorizeEventAccess($event, 'view');

        $ticketTypes = $event->ticketTypes()->orderBy('nama_tiket')->get();

        if ($request->expectsJson()) {
            return response()->json($ticketTypes);
        }

        return view('ticket-types.index', compact('event', 'ticketTypes'));
    }

    public function create(Event $event): View
    {
        $this->authorizeEventAccess($event, 'manage');

        return view('ticket-types.create', compact('event'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $data = $request->validate([
            'nama_tiket' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
            'kuota' => ['required', 'integer', 'min:1'],
            'capacity' => ['nullable', 'integer', 'min:1', 'gte:kuota'],
        ]);

        $event->ticketTypes()->create([
            'nama_tiket' => $data['nama_tiket'],
            'harga' => $data['harga'],
            'kuota' => $data['kuota'],
            'capacity' => $data['capacity'] ?? $data['kuota'],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Jenis tiket berhasil ditambahkan'], 201);
        }

        return redirect()->route('ticket-types.index', $event)
            ->with('success', 'Jenis tiket berhasil ditambahkan');
    }

    public function edit(Event $event, string $ticketType): View
    {
        $this->authorizeEventAccess($event, 'manage');

        $ticketType = $event->ticketTypes()->findOrFail($ticketType);

        return view('ticket-types.edit', compact('event', 'ticketType'));
    }

    public function update(Request $request, Event $event, string $ticketType): RedirectResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $ticketType = $event->ticketTypes()->findOrFail($ticketType);

        $data = $request->validate([
            'nama_tiket' => ['sometimes', 'required', 'string', 'max:255'],
            'harga' => ['sometimes', 'required', 'numeric', 'min:0'],
            'kuota' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);

        $issued = $ticketType->tickets()
            ->where('status', '!=', TicketStatus::Cancelled->value)
            ->count();

        $kuota = $data['kuota'] ?? $ticketType->kuota;

        abort_if($kuota + $issued > $ticketType->capacity, 422, 'Kuota melebihi kapasitas tiket.');

        $ticketType->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Jenis tiket berhasil diperbarui']);
        }

        return redirect()->route('ticket-types.index', $event)
            ->with('success', 'Jenis tiket berhasil diperbarui');
    }

    public function destroy(Request $request, Event $event, string $ticketType): RedirectResponse|JsonResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $event->ticketTypes()->findOrFail($ticketType)->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Jenis tiket berhasil dihapus']);
        }

        return redirect()->route('ticket-types.index', $event)
            ->with('success', 'Jenis tiket berhasil dihapus');
    }
}
