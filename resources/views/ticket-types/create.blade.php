@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Page Header & Back Navigation -->
    <div class="flex items-center justify-between pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wider text-qr-text inline-block border-b-2 border-qr-accent pb-1">
                TAMBAH JENIS TIKET
            </h1>
            <p class="text-xs sm:text-sm text-qr-muted mt-2 tracking-wide">
                Buat kategori tiket baru dan atur kapasitas serta harganya.
            </p>
        </div>
        <a href="{{ Route::has('ticket-types.index') ? route('ticket-types.index') : '#' }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-qr-border bg-qr-surface hover:bg-qr-card text-qr-muted hover:text-qr-text text-xs uppercase tracking-wider font-semibold transition duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-qr-card border border-qr-border rounded-xl shadow-xl p-6 sm:p-8 transition duration-200 hover:border-qr-accent/40 hover:shadow-[0_0_30px_rgba(139,92,246,0.12)]">
        <form action="{{ Route::has('ticket-types.store') ? route('ticket-types.store') : '#' }}" method="POST" class="space-y-6">
            @csrf

            <!-- Field: Event -->
            <div>
                <label for="event_id" class="block text-xs font-bold uppercase tracking-wider text-qr-muted mb-2">
                    Event Terkait <span class="text-qr-danger">*</span>
                </label>
                <div class="relative">
                    <select
                        name="event_id"
                        id="event_id"
                        required
                        class="w-full bg-qr-surface border @error('event_id') border-qr-danger @else border-qr-border @enderror rounded-lg px-4 py-3 text-qr-text text-sm focus:outline-none focus:border-qr-accent focus:ring-1 focus:ring-qr-accent transition duration-150 appearance-none cursor-pointer pr-10"
                    >
                        <option value="" disabled {{ old('event_id') ? '' : 'selected' }} class="bg-qr-surface text-qr-muted">
                            -- Pilih Event --
                        </option>
                        @foreach ($events as $event)
                            <option
                                value="{{ $event->id }}"
                                {{ old('event_id') == $event->id ? 'selected' : '' }}
                                class="bg-qr-surface text-qr-text"
                            >
                                {{ $event->nama_event }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-qr-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                @error('event_id')
                    <p class="mt-1.5 text-xs text-qr-danger flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Field: Nama Tiket -->
            <div>
                <label for="nama_tiket" class="block text-xs font-bold uppercase tracking-wider text-qr-muted mb-2">
                    Nama Tiket <span class="text-qr-danger">*</span>
                </label>
                <input
                    type="text"
                    name="nama_tiket"
                    id="nama_tiket"
                    value="{{ old('nama_tiket') }}"
                    required
                    placeholder="Contoh: VIP Pass, Early Bird, General Admission"
                    class="w-full bg-qr-surface border @error('nama_tiket') border-qr-danger @else border-qr-border @enderror rounded-lg px-4 py-3 text-qr-text placeholder-qr-muted text-sm focus:outline-none focus:border-qr-accent focus:ring-1 focus:ring-qr-accent transition duration-150"
                >
                @error('nama_tiket')
                    <p class="mt-1.5 text-xs text-qr-danger flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- 2 Columns Grid: Harga & Kuota -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Field: Harga -->
                <div>
                    <label for="harga" class="block text-xs font-bold uppercase tracking-wider text-qr-muted mb-2">
                        Harga Tiket (IDR) <span class="text-qr-danger">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-mono font-bold text-xs text-qr-accent-light">
                            Rp
                        </span>
                        <input
                            type="number"
                            name="harga"
                            id="harga"
                            min="0"
                            step="1000"
                            value="{{ old('harga') }}"
                            required
                            placeholder="0"
                            class="w-full bg-qr-surface border @error('harga') border-qr-danger @else border-qr-border @enderror rounded-lg pl-12 pr-4 py-3 text-qr-text font-mono text-sm placeholder-qr-muted focus:outline-none focus:border-qr-accent focus:ring-1 focus:ring-qr-accent transition duration-150"
                        >
                    </div>
                    <p class="mt-1 text-[11px] text-qr-muted">
                        Masukkan 0 untuk tiket gratis.
                    </p>
                    @error('harga')
                        <p class="mt-1.5 text-xs text-qr-danger flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Field: Kuota -->
                <div>
                    <label for="kuota" class="block text-xs font-bold uppercase tracking-wider text-qr-muted mb-2">
                        Kuota / Kapasitas <span class="text-qr-danger">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            name="kuota"
                            id="kuota"
                            min="1"
                            value="{{ old('kuota') }}"
                            required
                            placeholder="Contoh: 100"
                            class="w-full bg-qr-surface border @error('kuota') border-qr-danger @else border-qr-border @enderror rounded-lg px-4 py-3 text-qr-text font-mono text-sm placeholder-qr-muted focus:outline-none focus:border-qr-accent focus:ring-1 focus:ring-qr-accent transition duration-150"
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-medium text-qr-muted">
                            Tiket
                        </span>
                    </div>
                    <p class="mt-1 text-[11px] text-qr-muted">
                        Jumlah maksimal tiket yang dapat dijual.
                    </p>
                    @error('kuota')
                        <p class="mt-1.5 text-xs text-qr-danger flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-qr-border">
                <a href="{{ Route::has('ticket-types.index') ? route('ticket-types.index') : '#' }}" class="px-5 py-2.5 rounded-lg border border-qr-border bg-qr-surface hover:bg-qr-card text-qr-muted hover:text-qr-text font-semibold text-xs sm:text-sm uppercase tracking-wider transition duration-150">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-qr-accent hover:bg-qr-accent-dark text-white font-semibold text-xs sm:text-sm uppercase tracking-wider transition duration-150 shadow-lg shadow-qr-accent/25 hover:shadow-qr-accent/40 hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Tiket</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection