<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index(Event $event): Collection
    {
        $this->authorizeEventAccess($event, 'view');

        return $event->ticketTypes()->orderBy('nama_tiket')->get();
    }

    public function store(Request $request, Event $event): TicketType
    {
        $this->authorizeEventAccess($event, 'update');

        $data = $request->validate([
            'nama_tiket' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
            'kuota' => ['required', 'integer', 'min:1'],
            'capacity' => ['nullable', 'integer', 'min:1', 'gte:kuota'],
        ]);

        $capacity = $data['capacity'] ?? $data['kuota'];

        return $event->ticketTypes()->create([
            'nama_tiket' => $data['nama_tiket'],
            'harga' => $data['harga'],
            'kuota' => $data['kuota'],
            'capacity' => $capacity,
        ]);
    }

    public function update(Request $request, Event $event, string $ticketType): TicketType
    {
        $this->authorizeEventAccess($event, 'update');

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

        return $ticketType;
    }

    public function destroy(Event $event, string $ticketType): JsonResponse
    {
        $this->authorizeEventAccess($event, 'update');

        $event->ticketTypes()->findOrFail($ticketType)->delete();

        return response()->json([
            'message' => 'Jenis tiket berhasil dihapus',
        ]);
    }
}
