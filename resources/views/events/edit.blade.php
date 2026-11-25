@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-qr-border">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-qr-accent-glow text-qr-accent border border-qr-accent/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-qr-accent animate-pulse"></span>
                    Event Management
                </span>

                @if (isset($event->status))
                    @if ($event->status === 'published' || $event->status === 'aktif')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-qr-success/10 text-qr-success border border-qr-success/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-qr-success"></span>
                            {{ $event->status }}
                        </span>
                    @elseif ($event->status === 'draft')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-qr-warning/10 text-qr-warning border border-qr-warning/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-qr-warning"></span>
                            Draft
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-qr-surface text-qr-muted border border-qr-border">
                            <span class="w-1.5 h-1.5 rounded-full bg-qr-muted"></span>
                            {{ $event->status }}
                        </span>
                    @endif
                @endif
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-qr-text uppercase">
                Edit Event
            </h1>
            <p class="text-sm text-qr-muted mt-1">
                Perbarui informasi detail dan jadwal pelaksanaan event Qreven.
            </p>
        </div>
        <div>
            <a href="{{ Route::has('events.index') ? route('events.index') : '#' }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-qr-muted hover:text-qr-text bg-qr-surface border border-qr-border rounded-lg transition hover:border-qr-accent/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="p-4 rounded-lg bg-qr-success/10 border border-qr-success/30 text-qr-success flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 1118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide">Berhasil</p>
                <p class="text-sm mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-lg bg-qr-danger/10 border border-qr-danger/30 text-qr-danger flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 1118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide">Terjadi Kesalahan</p>
                <p class="text-sm mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Form Card Container -->
    <div class="bg-qr-card border border-qr-border rounded-xl p-6 sm:p-8 shadow-xl transition-all duration-300 hover:border-qr-accent/40 hover:shadow-[0_0_25px_rgba(139,92,246,0.1)]">
        <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section Header: Informasi Event -->
            <div class="border-b border-qr-border/60 pb-3">
                <h2 class="text-xs font-semibold tracking-wider uppercase text-qr-accent flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-qr-accent"></span>
                    Informasi Detail Event
                </h2>
            </div>

            <!-- Nama Event -->
            <div>
                <label for="nama_event" class="block text-sm text-qr-muted uppercase tracking-wide font-medium mb-2">
                    Nama Event <span class="text-qr-danger">*</span>
                </label>
                <input
                    type="text"
                    name="nama_event"
                    id="nama_event"
                    value="{{ old('nama_event', $event->nama_event ?? '') }}"
                    required
                    placeholder="Masukkan nama event"
                    class="w-full bg-qr-surface border @error('nama_event') border-qr-danger @else border-qr-border @enderror rounded-lg text-qr-text focus:border-qr-accent focus:ring-1 focus:ring-qr-accent focus:outline-none px-4 py-3 transition placeholder:text-qr-muted/40">
                @error('nama_event')
                    <p class="text-qr-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi Event -->
            <div>
                <label for="deskripsi" class="block text-sm text-qr-muted uppercase tracking-wide font-medium mb-2">
                    Deskripsi <span class="text-qr-danger">*</span>
                </label>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="5"
                    required
                    placeholder="Tuliskan deskripsi lengkap mengenai event..."
                    class="w-full bg-qr-surface border @error('deskripsi') border-qr-danger @else border-qr-border @enderror rounded-lg text-qr-text focus:border-qr-accent focus:ring-1 focus:ring-qr-accent focus:outline-none px-4 py-3 transition placeholder:text-qr-muted/40">{{ old('deskripsi', $event->deskripsi ?? '') }}</textarea>
                @error('deskripsi')
                    <p class="text-qr-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lokasi Event -->
            <div>
                <label for="lokasi" class="block text-sm text-qr-muted uppercase tracking-wide font-medium mb-2">
                    Lokasi <span class="text-qr-danger">*</span>
                </label>
                <input
                    type="text"
                    name="lokasi"
                    id="lokasi"
                    value="{{ old('lokasi', $event->lokasi ?? '') }}"
                    required
                    placeholder="Masukkan lokasi event"
                    class="w-full bg-qr-surface border @error('lokasi') border-qr-danger @else border-qr-border @enderror rounded-lg text-qr-text focus:border-qr-accent focus:ring-1 focus:ring-qr-accent focus:outline-none px-4 py-3 transition placeholder:text-qr-muted/40">
                @error('lokasi')
                    <p class="text-qr-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Section Header: Jadwal -->
            <div class="border-b border-qr-border/60 pb-3 pt-2">
                <h2 class="text-xs font-semibold tracking-wider uppercase text-qr-accent flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-qr-accent"></span>
                    Jadwal Pelaksanaan
                </h2>
            </div>

            <!-- Tanggal Mulai & Selesai (Grid 2 Kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <!-- Tanggal Mulai -->
                <div>
                    <label for="tanggal_mulai" class="block text-sm text-qr-muted uppercase tracking-wide font-medium mb-2">
                        Tanggal Mulai <span class="text-qr-danger">*</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal_mulai"
                        id="tanggal_mulai"
                        value="{{ old('tanggal_mulai', !empty($event->tanggal_mulai) ? \Carbon\Carbon::parse($event->tanggal_mulai)->format('Y-m-d') : '') }}"
                        required
                        class="w-full bg-qr-surface border @error('tanggal_mulai') border-qr-danger @else border-qr-border @enderror rounded-lg text-qr-text focus:border-qr-accent focus:ring-1 focus:ring-qr-accent focus:outline-none px-4 py-3 transition [color-scheme:dark] cursor-pointer">
                    @error('tanggal_mulai')
                        <p class="text-qr-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label for="tanggal_selesai" class="block text-sm text-qr-muted uppercase tracking-wide font-medium mb-2">
                        Tanggal Selesai <span class="text-qr-danger">*</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal_selesai"
                        id="tanggal_selesai"
                        value="{{ old('tanggal_selesai', !empty($event->tanggal_selesai) ? \Carbon\Carbon::parse($event->tanggal_selesai)->format('Y-m-d') : '') }}"
                        required
                        class="w-full bg-qr-surface border @error('tanggal_selesai') border-qr-danger @else border-qr-border @enderror rounded-lg text-qr-text focus:border-qr-accent focus:ring-1 focus:ring-qr-accent focus:outline-none px-4 py-3 transition [color-scheme:dark] cursor-pointer">
                    @error('tanggal_selesai')
                        <p class="text-qr-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section Header: Media Poster -->
            <div class="border-b border-qr-border/60 pb-3 pt-2">
                <h2 class="text-xs font-semibold tracking-wider uppercase text-qr-accent flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-qr-accent"></span>
                    Media & Poster
                </h2>
            </div>

            <!-- Poster Event (Optional) -->
            <div>
                <label for="poster" class="block text-sm text-qr-muted uppercase tracking-wide font-medium mb-2">
                    Poster <span class="text-xs font-normal normal-case text-qr-muted">(Opsional)</span>
                </label>

                @if (!empty($event->poster))
                    <div class="mb-3 flex items-center gap-4 p-3 rounded-lg bg-qr-surface border border-qr-border">
                        <img
                            src="{{ asset('storage/' . $event->poster) }}"
                            alt="{{ $event->nama_event ?? 'Poster Event' }}"
                            class="w-16 h-16 object-cover rounded-md border border-qr-border shrink-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wider text-qr-accent-light">Poster Saat Ini</p>
                            <p class="text-xs text-qr-muted truncate mt-0.5">{{ basename($event->poster) }}</p>
                        </div>
                    </div>
                @endif

                <input
                    type="file"
                    name="poster"
                    id="poster"
                    accept="image/*"
                    class="w-full bg-qr-surface border @error('poster') border-qr-danger @else border-qr-border @enderror rounded-lg text-qr-text focus:border-qr-accent focus:ring-1 focus:ring-qr-accent focus:outline-none px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:uppercase file:tracking-wider file:bg-qr-accent/20 file:text-qr-accent-light hover:file:bg-qr-accent/30 file:cursor-pointer transition">
                @error('poster')
                    <p class="text-qr-danger text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-qr-muted mt-2">Biarkan kosong jika tidak ingin mengubah poster event.</p>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-qr-border flex flex-col-reverse sm:flex-row items-center justify-end gap-3 sm:gap-4">
                <a
                    href="{{ Route::has('events.index') ? route('events.index') : '#' }}"
                    class="w-full sm:w-auto text-center text-qr-muted hover:text-qr-text font-medium px-4 py-3 transition">
                    Batal
                </a>
                <button
                    type="submit"
                    class="w-full sm:w-auto bg-qr-accent hover:bg-qr-accent-dark text-white px-6 py-3 rounded-lg font-semibold transition cursor-pointer shadow-lg shadow-qr-accent/25 hover:shadow-qr-accent/40">
                    Perbarui Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection