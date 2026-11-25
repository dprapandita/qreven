@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between pb-6 border-b border-qr-border">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-wider uppercase text-qr-text flex items-center gap-3">
                <span class="text-qr-accent">✦</span> TAMBAH BOOTH
            </h1>
            <p class="text-sm text-qr-muted mt-1">Buat booth baru untuk melengkapi event kamu</p>
        </div>
        <a href="{{ Route::has('booths.index') ? route('booths.index') : '#' }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-qr-border hover:border-qr-accent/50 text-qr-muted hover:text-qr-text bg-qr-surface hover:bg-qr-card text-sm font-semibold transition duration-150">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-qr-card border border-qr-border rounded-2xl p-6 sm:p-8 shadow-xl">
        <form action="{{ Route::has('booths.store') ? route('booths.store') : '#' }}" method="POST" class="space-y-6">
            @csrf

            <!-- Field: Event (Select) -->
            <div class="space-y-2">
                <label for="event_id" class="block text-xs font-bold uppercase tracking-wider text-qr-text">
                    Pilih Event <span class="text-qr-accent">*</span>
                </label>
                <div class="relative">
                    <select name="event_id" id="event_id" 
                            class="w-full bg-qr-surface border @error('event_id') border-qr-danger @else border-qr-border @enderror rounded-xl px-4 py-3 text-qr-text text-sm focus:outline-none focus:border-qr-accent focus:ring-1 focus:ring-qr-accent transition duration-150 appearance-none">
                        <option value="" disabled {{ old('event_id') ? '' : 'selected' }} class="text-qr-muted">
                            -- Pilih Salah Satu Event --
                        </option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }} class="bg-qr-surface text-qr-text">
                                {{ $event->nama_event ?? $event->name ?? 'Event #' . $event->id }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-qr-muted">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                @error('event_id')
                    <p class="text-xs text-qr-danger mt-1 flex items-center gap-1">
                        <span>⚠</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Field: Nama Booth (Text) -->
            <div class="space-y-2">
                <label for="nama_booth" class="block text-xs font-bold uppercase tracking-wider text-qr-text">
                    Nama Booth <span class="text-qr-accent">*</span>
                </label>
                <input type="text" name="nama_booth" id="nama_booth" 
                       value="{{ old('nama_booth') }}" 
                       placeholder="Contoh: Booth Alpha A-01" 
                       class="w-full bg-qr-surface border @error('nama_booth') border-qr-danger @else border-qr-border @enderror rounded-xl px-4 py-3 text-qr-text placeholder-qr-muted/60 text-sm focus:outline-none focus:border-qr-accent focus:ring-1 focus:ring-qr-accent transition duration-150"
                       required>
                @error('nama_booth')
                    <p class="text-xs text-qr-danger mt-1 flex items-center gap-1">
                        <span>⚠</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Field: Deskripsi (Textarea) -->
            <div class="space-y-2">
                <label for="deskripsi" class="block text-xs font-bold uppercase tracking-wider text-qr-text">
                    Deskripsi Booth
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="4" 
                          placeholder="Jelaskan ukuran, fasilitas (listrik, meja, kursi), atau lokasi booth..." 
                          class="w-full bg-qr-surface border @error('deskripsi') border-qr-danger @else border-qr-border @enderror rounded-xl px-4 py-3 text-qr-text placeholder-qr-muted/60 text-sm focus:outline-none focus:border-qr-accent focus:ring-1 focus:ring-qr-accent transition duration-150 resize-y">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-xs text-qr-danger mt-1 flex items-center gap-1">
                        <span>⚠</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Field: Harga (Number) -->
            <div class="space-y-2">
                <label for="harga" class="block text-xs font-bold uppercase tracking-wider text-qr-text">
                    Harga Sewa (Rp) <span class="text-qr-accent">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-sm font-semibold text-qr-muted">
                        Rp
                    </span>
                    <input type="number" name="harga" id="harga" min="0" step="1000"
                           value="{{ old('harga') }}" 
                           placeholder="500000" 
                           class="w-full bg-qr-surface border @error('harga') border-qr-danger @else border-qr-border @enderror rounded-xl pl-12 pr-4 py-3 text-qr-text placeholder-qr-muted/60 text-sm focus:outline-none focus:border-qr-accent focus:ring-1 focus:ring-qr-accent transition duration-150"
                           required>
                </div>
                @error('harga')
                    <p class="text-xs text-qr-danger mt-1 flex items-center gap-1">
                        <span>⚠</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-qr-border flex items-center justify-end gap-3">
                <a href="{{ Route::has('booths.index') ? route('booths.index') : '#' }}" 
                   class="px-5 py-2.5 rounded-xl border border-qr-border hover:border-qr-accent-light/50 text-qr-muted hover:text-qr-text bg-qr-surface hover:bg-qr-card text-sm font-semibold transition duration-150">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-qr-accent hover:bg-qr-accent-dark text-white font-semibold text-sm shadow-lg shadow-qr-accent/25 hover:shadow-qr-accent/40 active:scale-[0.98] transition duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Booth</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection