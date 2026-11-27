@php
    $dt = fn ($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('Y-m-d\TH:i') : '';
    $statusValue = old('status', ($event->status ?? null) instanceof \BackedEnum ? $event->status->value : ($event->status ?? 'draft'));
@endphp
<div class="grid gap-6">
    <div class="grid gap-6 sm:grid-cols-2">
        <x-field name="nama_event" label="Nama event" required>
            <input id="nama_event" name="nama_event" type="text" class="input" value="{{ old('nama_event', $event->nama_event ?? '') }}" placeholder="Contoh: Qreven Fest 2026" required>
        </x-field>

        <x-field name="subdomain" label="Subdomain" help="Huruf kecil, angka, dan tanda hubung." required>
            <input id="subdomain" name="subdomain" type="text" class="input font-mono" value="{{ old('subdomain', $event->subdomain ?? '') }}" placeholder="qreven-fest" required>
        </x-field>
    </div>

    <x-field name="deskripsi" label="Deskripsi" help="Tampil di halaman publik event.">
        <textarea id="deskripsi" name="deskripsi" rows="5" class="input" placeholder="Ceritakan tentang event ini...">{{ old('deskripsi', $event->deskripsi ?? '') }}</textarea>
    </x-field>

    <x-field name="lokasi" label="Lokasi">
        <input id="lokasi" name="lokasi" type="text" class="input" value="{{ old('lokasi', $event->lokasi ?? '') }}" placeholder="Nama tempat / alamat">
    </x-field>

    <div class="grid gap-6 sm:grid-cols-2">
        <x-field name="tanggal_mulai" label="Mulai" required>
            <input id="tanggal_mulai" name="tanggal_mulai" type="datetime-local" class="input" value="{{ old('tanggal_mulai', $dt($event->tanggal_mulai ?? null)) }}" required>
        </x-field>
        <x-field name="tanggal_selesai" label="Selesai">
            <input id="tanggal_selesai" name="tanggal_selesai" type="datetime-local" class="input" value="{{ old('tanggal_selesai', $dt($event->tanggal_selesai ?? null)) }}">
        </x-field>
    </div>

    <x-field name="status" label="Status">
        <select id="status" name="status" class="input">
            <option value="draft" @selected($statusValue === 'draft')>Draft (belum tampil publik)</option>
            <option value="published" @selected($statusValue === 'published')>Terbit</option>
        </select>
    </x-field>
</div>
