@extends('layouts.app')

@section('content')
<div class="py-6 sm:py-8 max-w-3xl mx-auto space-y-6">
    {{-- Breadcrumb / Back Link --}}
    <div>
        <a href="{{ Route::has('rundowns.index') ? route('rundowns.index') : '#' }}"
           class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-qr-muted hover:text-qr-accent-light transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Rundown</span>
        </a>
    </div>

    {{-- Header Section --}}
    <div class="pb-4 border-b border-qr-border">
        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-qr-surface border border-qr-border text-qr-accent-light uppercase tracking-widest mb-2">
            <span class="w-1.5 h-1.5 rounded-full bg-qr-accent"></span>
            Edit Kegiatan
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wider text-qr-text">
            EDIT KEGIATAN RUNDOWN
        </h1>
        <p class="text-sm text-qr-muted mt-1">
            Perbarui data dan jadwal kegiatan untuk: <span class="text-qr-accent-light font-semibold">{{ $rundown->nama_kegiatan }}</span>
        </p>
    </div>

    {{-- Error Banner --}}
    @if ($errors->any())
        <div class="bg-qr-card border border-qr-danger/40 rounded-xl p-4 text-qr-danger shadow-lg shadow-qr-danger/5">
            <div class="flex items-center gap-2 font-semibold text-sm mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Terdapat kesalahan pada formulir:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 text-qr-muted">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-qr-card border border-qr-border rounded-2xl p-6 sm:p-8 shadow-xl shadow-black/40">
        <form action="{{ Route::has('rundowns.update') ? route('rundowns.update', $rundown->id) : '#' }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Event ID (Select) --}}
            <div>
                <label for="event_id" class="block text-xs font-bold uppercase tracking-wider text-qr-muted mb-2">
                    Pilih Event <span class="text-qr-danger">*</span>
                </label>
                <div class="relative">
                    <select name="event_id"
                            id="event_id"
                            required
                            class="w-full bg-qr-surface border @error('event_id') border-qr-danger @else border-qr-border @enderror focus:border-qr-accent focus:ring-1 focus:ring-qr-accent text-qr-text rounded-xl px-4 py-3 outline-none text-sm transition-all appearance-none cursor-pointer">
                        <option value="" disabled class="bg-qr-surface text-qr-muted">
                            -- Pilih Acara / Event --
                        </option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id', $rundown->event_id) == $event->id ? 'selected' : '' }} class="bg-qr-surface text-qr-text">
                                {{ $event->nama_event }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-qr-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('event_id')
                    <p class="text-xs text-qr-danger mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Nama Kegiatan (Text) --}}
            <div>
                <label for="nama_kegiatan" class="block text-xs font-bold uppercase tracking-wider text-qr-muted mb-2">
                    Nama Kegiatan <span class="text-qr-danger">*</span>
                </label>
                <input type="text"
                       name="nama_kegiatan"
                       id="nama_kegiatan"
                       value="{{ old('nama_kegiatan', $rundown->nama_kegiatan) }}"
                       required
                       placeholder="Contoh: Registrasi Peserta & Welcome Drink"
                       class="w-full bg-qr-surface border @error('nama_kegiatan') border-qr-danger @else border-qr-border @enderror focus:border-qr-accent focus:ring-1 focus:ring-qr-accent text-qr-text rounded-xl px-4 py-3 outline-none text-sm placeholder:text-qr-muted/40 transition-all">
                @error('nama_kegiatan')
                    <p class="text-xs text-qr-danger mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Waktu Mulai & Waktu Selesai (datetime-local) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="waktu_mulai" class="block text-xs font-bold uppercase tracking-wider text-qr-muted mb-2">
                        Waktu Mulai <span class="text-qr-danger">*</span>
                    </label>
                    <input type="datetime-local"
                           name="waktu_mulai"
                           id="waktu_mulai"
                           value="{{ old('waktu_mulai', $rundown->waktu_mulai ? \Carbon\Carbon::parse($rundown->waktu_mulai)->format('Y-m-d\TH:i') : '') }}"
                           required
                           class="w-full bg-qr-surface border @error('waktu_mulai') border-qr-danger @else border-qr-border @enderror focus:border-qr-accent focus:ring-1 focus:ring-qr-accent text-qr-text rounded-xl px-4 py-3 outline-none text-sm [color-scheme:dark] transition-all">
                    @error('waktu_mulai')
                        <p class="text-xs text-qr-danger mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="waktu_selesai" class="block text-xs font-bold uppercase tracking-wider text-qr-muted mb-2">
                        Waktu Selesai <span class="text-qr-danger">*</span>
                    </label>
                    <input type="datetime-local"
                           name="waktu_selesai"
                           id="waktu_selesai"
                           value="{{ old('waktu_selesai', $rundown->waktu_selesai ? \Carbon\Carbon::parse($rundown->waktu_selesai)->format('Y-m-d\TH:i') : '') }}"
                           required
                           class="w-full bg-qr-surface border @error('waktu_selesai') border-qr-danger @else border-qr-border @enderror focus:border-qr-accent focus:ring-1 focus:ring-qr-accent text-qr-text rounded-xl px-4 py-3 outline-none text-sm [color-scheme:dark] transition-all">
                    @error('waktu_selesai')
                        <p class="text-xs text-qr-danger mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi (Textarea, Optional) --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="deskripsi" class="block text-xs font-bold uppercase tracking-wider text-qr-muted">
                        Deskripsi Kegiatan
                    </label>
                    <span class="text-xs text-qr-muted/60">Opsional</span>
                </div>
                <textarea name="deskripsi"
                          id="deskripsi"
                          rows="4"
                          placeholder="Tambahkan detail kegiatan, pembicara, lokasi panggung, atau catatan penting..."
                          class="w-full bg-qr-surface border @error('deskripsi') border-qr-danger @else border-qr-border @enderror focus:border-qr-accent focus:ring-1 focus:ring-qr-accent text-qr-text rounded-xl p-4 outline-none text-sm placeholder:text-qr-muted/40 transition-all leading-relaxed">{{ old('deskripsi', $rundown->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-xs text-qr-danger mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-4 border-t border-qr-border">
                <a href="{{ Route::has('rundowns.index') ? route('rundowns.index') : '#' }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-qr-border hover:border-qr-accent text-qr-muted hover:text-white font-medium text-sm transition-all duration-200">
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-qr-accent hover:bg-qr-accent-dark text-white font-semibold text-sm shadow-lg shadow-qr-accent/25 hover:shadow-qr-accent/40 transition-all duration-200 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Perbarui Kegiatan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection