<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Rundown;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RundownController extends Controller
{
    public function index(Event $event): Collection
    {
        $this->authorizeEventAccess($event, 'view');

        return $event->rundowns()->orderBy('waktu_mulai')->get();
    }

    public function store(Request $request, Event $event): Rundown
    {
        $this->authorizeEventAccess($event, 'update');

        $data = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'waktu_mulai' => ['required', 'date'],
            'waktu_selesai' => ['required', 'date', 'after_or_equal:waktu_mulai'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        return $event->rundowns()->create($data);
    }

    public function update(Request $request, Event $event, string $rundown): Rundown
    {
        $this->authorizeEventAccess($event, 'update');

        $rundown = $event->rundowns()->findOrFail($rundown);

        $data = $request->validate([
            'nama_kegiatan' => ['sometimes', 'required', 'string', 'max:255'],
            'waktu_mulai' => ['sometimes', 'required', 'date'],
            'waktu_selesai' => ['sometimes', 'required', 'date'],
            'deskripsi' => ['sometimes', 'nullable', 'string'],
        ]);

        $start = Carbon::parse($data['waktu_mulai'] ?? $rundown->waktu_mulai);
        $end = Carbon::parse($data['waktu_selesai'] ?? $rundown->waktu_selesai);

        abort_if($end->lt($start), 422, 'Waktu selesai harus sama atau setelah waktu mulai.');

        $rundown->update($data);

        return $rundown;
    }

    public function destroy(Event $event, string $rundown): JsonResponse
    {
        $this->authorizeEventAccess($event, 'update');

        $event->rundowns()->findOrFail($rundown)->delete();

        return response()->json([
            'message' => 'Rundown berhasil dihapus',
        ]);
    }
}
