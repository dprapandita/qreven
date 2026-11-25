<?php

namespace App\Http\Controllers;

use App\Enums\BoothStatus;
use App\Models\Booth;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoothController extends Controller
{
    public function index(Event $event): Collection
    {
        $this->authorizeEventAccess($event, 'view');

        return $event->booths()->orderBy('nama_booth')->get();
    }

    public function store(Request $request, Event $event): Booth
    {
        $this->authorizeEventAccess($event, 'update');

        $data = $request->validate([
            'nama_booth' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        return $event->booths()->create([
            'nama_booth' => $data['nama_booth'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'harga' => $data['harga'],
            'status' => BoothStatus::Available->value,
        ]);
    }

    public function update(Request $request, Event $event, string $booth): Booth
    {
        $this->authorizeEventAccess($event, 'update');

        $booth = $event->booths()->findOrFail($booth);

        $data = $request->validate([
            'nama_booth' => ['sometimes', 'required', 'string', 'max:255'],
            'deskripsi' => ['sometimes', 'nullable', 'string'],
            'harga' => ['sometimes', 'required', 'numeric', 'min:0'],
        ]);

        $booth->update($data);

        return $booth;
    }

    public function destroy(Event $event, string $booth): JsonResponse
    {
        $this->authorizeEventAccess($event, 'update');

        $booth = $event->booths()->findOrFail($booth);

        abort_if($booth->bookings()->exists(), 422, 'Booth memiliki riwayat pemesanan.');

        $booth->delete();

        return response()->json([
            'message' => 'Booth berhasil dihapus',
        ]);
    }
}
