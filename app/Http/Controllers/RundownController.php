<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class RundownController extends Controller
{
    public function index(Request $request, Event $event): View|JsonResponse
    {
        $this->authorizeEventAccess($event, 'view');

        $rundowns = $event->rundowns()->orderBy('waktu_mulai')->get();

        if ($request->expectsJson()) {
            return response()->json($rundowns);
        }

        return view('rundowns.index', compact('event', 'rundowns'));
    }

    public function create(Event $event): View
    {
        $this->authorizeEventAccess($event, 'manage');

        return view('rundowns.create', compact('event'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $data = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'waktu_mulai' => ['required', 'date'],
            'waktu_selesai' => ['required', 'date', 'after_or_equal:waktu_mulai'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $event->rundowns()->create($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Rundown berhasil ditambahkan'], 201);
        }

        return redirect()->route('rundowns.index', $event)
            ->with('success', 'Rundown berhasil ditambahkan');
    }

    public function edit(Event $event, string $rundown): View
    {
        $this->authorizeEventAccess($event, 'manage');

        $rundown = $event->rundowns()->findOrFail($rundown);

        return view('rundowns.edit', compact('event', 'rundown'));
    }

    public function update(Request $request, Event $event, string $rundown): RedirectResponse
    {
        $this->authorizeEventAccess($event, 'manage');

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

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Rundown berhasil diperbarui']);
        }

        return redirect()->route('rundowns.index', $event)
            ->with('success', 'Rundown berhasil diperbarui');
    }

    public function destroy(Request $request, Event $event, string $rundown): RedirectResponse|JsonResponse
    {
        $this->authorizeEventAccess($event, 'manage');

        $event->rundowns()->findOrFail($rundown)->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Rundown berhasil dihapus']);
        }

        return redirect()->route('rundowns.index', $event)
            ->with('success', 'Rundown berhasil dihapus');
    }
}
