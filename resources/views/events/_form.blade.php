@php
    $dt = fn ($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('Y-m-d\TH:i') : '';
    $status = old('status', $event->status ?? 'draft');
@endphp
<div class="grid gap-6">
    <x-field name="name" label="Nama event" required>
        <input id="name" name="name" type="text" class="input" value="{{ old('name', $event->name ?? '') }}" placeholder="Contoh: Qreven Fest 2026" required>
    </x-field>

    <x-field name="description" label="Deskripsi" help="Tampil di halaman publik event.">
        <textarea id="description" name="description" rows="5" class="input" placeholder="Ceritakan tentang event ini...">{{ old('description', $event->description ?? '') }}</textarea>
    </x-field>

    <x-field name="venue" label="Lokasi">
        <input id="venue" name="venue" type="text" class="input" value="{{ old('venue', $event->venue ?? '') }}" placeholder="Nama tempat / alamat">
    </x-field>

    <div class="grid gap-6 sm:grid-cols-2">
        <x-field name="starts_at" label="Mulai" required>
            <input id="starts_at" name="starts_at" type="datetime-local" class="input" value="{{ old('starts_at', $dt($event->starts_at ?? null)) }}" required>
        </x-field>
        <x-field name="ends_at" label="Selesai">
            <input id="ends_at" name="ends_at" type="datetime-local" class="input" value="{{ old('ends_at', $dt($event->ends_at ?? null)) }}">
        </x-field>
    </div>

    <x-field name="status" label="Status">
        <select id="status" name="status" class="input">
            <option value="draft" @selected($status === 'draft')>Draft (belum tampil publik)</option>
            <option value="published" @selected($status === 'published')>Terbit</option>
        </select>
    </x-field>
</div>
