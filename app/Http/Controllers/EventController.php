<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $events = Event::query()
            ->where(function ($query) use ($user): void {
                $query->where('user_id', $user->id)
                    ->orWhereHas('members', fn ($members) => $members->whereKey($user->id));
            })
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('events.index', compact('events'));
    }

    public function show(Event $event): View
    {
        $this->ensureCanView($event);

        return view('events.show', compact('event'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Event::class);

        $request->merge([
            'subdomain' => Str::slug((string) $request->input('subdomain')),
        ]);

        $data = $request->validate($this->rules());

        $data['user_id'] = $request->user()->id;
        $data['timezone'] = $data['timezone'] ?? 'UTC';
        $data['status'] = $data['status'] ?? EventStatus::Draft->value;

        Event::create($data);

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil ditambahkan');
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventAccess($event, 'update');

        $request->merge([
            'subdomain' => Str::slug((string) $request->input('subdomain')),
        ]);

        $data = $request->validate($this->rules($event));

        $event->update($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event berhasil diperbarui');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeEventAccess($event, 'delete');

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil dihapus');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(?Event $event = null): array
    {
        return [
            'nama_event' => ['required', 'string', 'max:255'],
            'subdomain' => [
                'required',
                'string',
                'max:63',
                'regex:/^[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/',
                Rule::notIn(config('events.reserved_subdomains')),
                Rule::unique('events', 'subdomain')->ignore($event?->id),
            ],
            'deskripsi' => ['nullable', 'string'],
            'lokasi' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'timezone' => ['nullable', 'timezone'],
            'status' => ['nullable', Rule::enum(EventStatus::class)],
        ];
    }
}
