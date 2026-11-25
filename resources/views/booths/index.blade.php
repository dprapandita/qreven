@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-qr-border">
        <div>
            <h1 class="text-3xl font-extrabold tracking-wider uppercase text-qr-text flex items-center gap-3">
                <span class="text-qr-accent">✦</span> BOOTH
            </h1>
            <p class="text-sm text-qr-muted mt-1">Kelola booth untuk event kamu</p>
        </div>
        <div>
            <a href="{{ Route::has('booths.create') ? route('booths.create') : '#' }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-qr-accent hover:bg-qr-accent-dark text-white font-semibold text-sm shadow-lg shadow-qr-accent/25 hover:shadow-qr-accent/40 active:scale-[0.98] transition duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Tambah Booth</span>
            </a>
        </div>
    </div>

    <!-- Booths Grid: 3 columns desktop, 1 mobile -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($booths as $booth)
            <div class="group bg-qr-card border border-qr-border rounded-2xl p-6 flex flex-col justify-between hover:border-qr-accent/60 hover:shadow-[0_0_30px_rgba(139,92,246,0.18)] transition-all duration-300">
                <div class="space-y-4">
                    <!-- Top Info: Event Name & Status Badge -->
                    <div class="flex items-start justify-between gap-2">
                        <span class="inline-flex items-center text-xs font-bold tracking-wider uppercase px-2.5 py-1 rounded-md bg-qr-surface border border-qr-border text-qr-accent-light">
                            {{ $booth->event->nama_event ?? $booth->event->name ?? 'Event' }}
                        </span>

                        <!-- Status Badge (tersedia=success, dipesan=warning, penuh=danger) -->
                        @if($booth->status === 'tersedia')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-success/15 text-qr-success border border-qr-success/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-qr-success animate-pulse"></span>
                                Tersedia
                            </span>
                        @elseif($booth->status === 'dipesan')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-warning/15 text-qr-warning border border-qr-warning/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-qr-warning"></span>
                                Dipesan
                            </span>
                        @elseif($booth->status === 'penuh')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-danger/15 text-qr-danger border border-qr-danger/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-qr-danger"></span>
                                Penuh
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-surface text-qr-muted border border-qr-border">
                                <span class="w-1.5 h-1.5 rounded-full bg-qr-muted"></span>
                                {{ ucfirst($booth->status) }}
                            </span>
                        @endif
                    </div>

                    <!-- Booth Name & Description -->
                    <div>
                        <h3 class="text-xl font-bold text-qr-text tracking-wide group-hover:text-qr-accent-light transition duration-200">
                            {{ $booth->nama_booth }}
                        </h3>
                        <p class="text-sm text-qr-muted mt-2 line-clamp-2 leading-relaxed">
                            {{ $booth->deskripsi ?? 'Tidak ada deskripsi tersedia untuk booth ini.' }}
                        </p>
                    </div>
                </div>

                <!-- Bottom Info: Price & Action -->
                <div class="mt-6 pt-4 border-t border-qr-border flex items-center justify-between">
                    <div>
                        <span class="text-xs text-qr-muted block">Harga Sewa</span>
                        <span class="text-lg font-extrabold text-qr-accent">
                            Rp {{ number_format($booth->harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ Route::has('booths.edit') ? route('booths.edit', $booth->id) : '#' }}" 
                           class="p-2 rounded-lg border border-qr-border hover:border-qr-accent/50 text-qr-muted hover:text-qr-text bg-qr-surface hover:bg-qr-surface/80 transition duration-150"
                           title="Edit Booth">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-qr-card border border-dashed border-qr-border rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-qr-surface border border-qr-border text-qr-accent flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-qr-text uppercase tracking-wide">Belum Ada Booth</h3>
                    <p class="text-sm text-qr-muted mt-1 max-w-md mx-auto">
                        Belum ada booth yang ditambahkan untuk event kamu. Mulai buat booth baru sekarang.
                    </p>
                    <div class="mt-6">
                        <a href="{{ Route::has('booths.create') ? route('booths.create') : '#' }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-qr-accent hover:bg-qr-accent-dark text-white font-semibold text-sm shadow-lg shadow-qr-accent/25 transition duration-200">
                            + Tambah Booth Pertama
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection