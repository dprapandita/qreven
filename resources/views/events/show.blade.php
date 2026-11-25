@extends('layouts.app')

@section('title', $event->nama_event)

@section('content')
<div class="space-y-8">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-center justify-between p-4 rounded-xl bg-qr-success/10 border border-qr-success/30 text-qr-success">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-qr-success/70 hover:text-qr-success cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-center justify-between p-4 rounded-xl bg-qr-danger/10 border border-qr-danger/30 text-qr-danger">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-qr-danger/70 hover:text-qr-danger cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Top Bar: Back Link & Action Buttons --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="{{ Route::has('events.index') ? route('events.index') : '#' }}" class="inline-flex items-center gap-2 text-sm font-semibold text-qr-muted hover:text-qr-accent-light transition duration-200 group w-fit">
            <span class="transition-transform group-hover:-translate-x-1">←</span>
            <span>Kembali ke Events</span>
        </a>

        <div class="flex items-center gap-3">
            <a href="{{ Route::has('events.edit') ? route('events.edit', $event->id) : '#' }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-qr-text bg-qr-surface border border-qr-border hover:border-qr-accent hover:text-qr-accent-light transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit</span>
            </a>

            <form action="{{ Route::has('events.destroy') ? route('events.destroy', $event->id) : '#' }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-qr-danger bg-qr-card border border-qr-danger/40 hover:bg-qr-danger/10 hover:border-qr-danger transition duration-200 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Event Header --}}
    <div class="bg-qr-card border border-qr-border rounded-2xl p-6 sm:p-8 relative overflow-hidden backdrop-blur-sm">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-qr-accent/10 rounded-full blur-3xl pointer-events-none"></div>

        @if ($event->poster)
            <div class="mb-6 rounded-xl overflow-hidden border border-qr-border max-h-80 w-full bg-qr-surface">
                <img src="{{ Str::startsWith($event->poster, ['http://', 'https://']) ? $event->poster : asset('storage/' . $event->poster) }}" alt="{{ $event->nama_event }}" class="w-full h-64 sm:h-80 object-cover">
            </div>
        @endif

        {{-- Status Badge --}}
        <div class="flex flex-wrap items-center gap-3 mb-3">
            @php
                $eventStatus = strtolower($event->status ?? 'draft');
            @endphp
            @if ($eventStatus === 'published')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-success/10 border border-qr-success/30 text-qr-success">
                    <span class="w-2 h-2 rounded-full bg-qr-success animate-pulse"></span>
                    Published
                </span>
            @elseif ($eventStatus === 'selesai')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-warning/10 border border-qr-warning/30 text-qr-warning">
                    <span class="w-2 h-2 rounded-full bg-qr-warning"></span>
                    Selesai
                </span>
            @elseif ($eventStatus === 'draft')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-surface border border-qr-border text-qr-muted">
                    <span class="w-2 h-2 rounded-full bg-qr-muted"></span>
                    Draft
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-accent-glow border border-qr-accent/30 text-qr-accent-light">
                    <span class="w-2 h-2 rounded-full bg-qr-accent"></span>
                    {{ ucfirst($event->status) }}
                </span>
            @endif
        </div>

        {{-- Event Name --}}
        <h1 class="text-3xl font-extrabold text-qr-text tracking-tight mb-4">
            {{ $event->nama_event }}
        </h1>

        {{-- Location & Date Range --}}
        <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-sm text-qr-muted mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-qr-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ $event->lokasi }}</span>
            </div>

            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-qr-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>
                    {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }}
                    @if ($event->tanggal_selesai && $event->tanggal_selesai !== $event->tanggal_mulai)
                        - {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }}
                    @endif
                </span>
            </div>
        </div>

        {{-- Description --}}
        <div class="text-qr-muted text-sm sm:text-base leading-relaxed border-t border-qr-border/60 pt-5">
            <p class="whitespace-pre-line">{{ $event->deskripsi }}</p>
        </div>
    </div>

    {{-- Section 1: JENIS TIKET --}}
    <section class="space-y-4">
        <div class="border-b border-qr-border pb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold uppercase tracking-widest text-qr-accent-light flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-qr-accent"></span>
                JENIS TIKET
            </h2>
            <span class="text-xs font-semibold text-qr-muted bg-qr-surface px-2.5 py-1 rounded-full border border-qr-border">
                {{ $event->ticketTypes->count() }} Tipe
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($event->ticketTypes as $ticketType)
                <div class="bg-qr-card border border-qr-border rounded-2xl p-5 hover:border-qr-accent/50 hover:shadow-[0_0_20px_rgba(139,92,246,0.15)] transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <h3 class="text-lg font-bold text-qr-text group-hover:text-qr-accent-light transition duration-150">
                                {{ $ticketType->nama_tiket }}
                            </h3>
                            <span class="p-1.5 rounded-lg bg-qr-accent-glow text-qr-accent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </span>
                        </div>
                        <div class="mb-4">
                            <div class="text-2xl font-extrabold text-qr-text">
                                Rp {{ number_format($ticketType->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-qr-border/60 flex items-center justify-between text-xs text-qr-muted">
                        <span>Kuota</span>
                        <span class="font-semibold text-qr-text bg-qr-surface px-2.5 py-1 rounded-md border border-qr-border">
                            {{ number_format($ticketType->kuota) }} tiket
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-qr-card/40 border border-qr-border border-dashed rounded-2xl p-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-qr-muted/50 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <p class="text-sm font-medium text-qr-muted">Belum ada jenis tiket yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Section 2: BOOTH --}}
    <section class="space-y-4">
        <div class="border-b border-qr-border pb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold uppercase tracking-widest text-qr-accent-light flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-qr-accent"></span>
                BOOTH
            </h2>
            <span class="text-xs font-semibold text-qr-muted bg-qr-surface px-2.5 py-1 rounded-full border border-qr-border">
                {{ $event->booths->count() }} Booth
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($event->booths as $booth)
                <div class="bg-qr-card border border-qr-border rounded-2xl p-5 hover:border-qr-accent/50 hover:shadow-[0_0_20px_rgba(139,92,246,0.15)] transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <h3 class="text-base font-bold text-qr-text group-hover:text-qr-accent-light transition duration-150">
                                {{ $booth->nama_booth }}
                            </h3>
                            @php
                                $boothStatus = strtolower($booth->status ?? 'tersedia');
                            @endphp
                            @if ($boothStatus === 'tersedia')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-qr-success/10 border border-qr-success/30 text-qr-success shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-qr-success"></span>
                                    Tersedia
                                </span>
                            @elseif (in_array($boothStatus, ['dipesan', 'terisi']))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-qr-warning/10 border border-qr-warning/30 text-qr-warning shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-qr-warning"></span>
                                    {{ ucfirst($booth->status) }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-qr-danger/10 border border-qr-danger/30 text-qr-danger shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-qr-danger"></span>
                                    {{ ucfirst($booth->status) }}
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-qr-muted line-clamp-3 mb-4 leading-relaxed">
                            {{ $booth->deskripsi ?: 'Tidak ada deskripsi booth.' }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-qr-border/60 flex items-center justify-between text-xs">
                        <span class="text-qr-muted">Harga Sewa</span>
                        <span class="text-base font-extrabold text-qr-accent-light">
                            Rp {{ number_format($booth->harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-qr-card/40 border border-qr-border border-dashed rounded-2xl p-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-qr-muted/50 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-sm font-medium text-qr-muted">Belum ada booth yang tersedia untuk event ini.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Section 3: RUNDOWN --}}
    <section class="space-y-4">
        <div class="border-b border-qr-border pb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold uppercase tracking-widest text-qr-accent-light flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-qr-accent"></span>
                RUNDOWN
            </h2>
            <span class="text-xs font-semibold text-qr-muted bg-qr-surface px-2.5 py-1 rounded-full border border-qr-border">
                {{ $event->rundowns->count() }} Agenda
            </span>
        </div>

        <div class="bg-qr-card border border-qr-border rounded-2xl p-6 sm:p-8">
            <div class="relative pl-6 sm:pl-8 space-y-6 before:absolute before:left-2 sm:before:left-3 before:top-3 before:bottom-3 before:w-0.5 before:bg-qr-border">
                @forelse ($event->rundowns->sortBy('waktu_mulai') as $rundown)
                    <div class="relative group">
                        {{-- Timeline Indicator Dot --}}
                        <div class="absolute -left-[29px] sm:-left-[37px] top-1.5 w-4 h-4 rounded-full bg-qr-card border-2 border-qr-accent group-hover:bg-qr-accent group-hover:shadow-[0_0_12px_rgba(139,92,246,0.6)] transition-all duration-200"></div>

                        <div class="bg-qr-surface/60 border border-qr-border rounded-xl p-4 sm:p-5 hover:border-qr-accent/40 transition duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                                <h3 class="text-base font-bold text-qr-text group-hover:text-qr-accent-light transition duration-150">
                                    {{ $rundown->nama_kegiatan }}
                                </h3>
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-qr-accent-light bg-qr-accent-glow px-2.5 py-1 rounded-md border border-qr-accent/20 w-fit shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($rundown->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($rundown->waktu_selesai)->format('H:i') }}
                                </span>
                            </div>
                            @if ($rundown->deskripsi)
                                <p class="text-sm text-qr-muted leading-relaxed">
                                    {{ $rundown->deskripsi }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-10 h-10 mx-auto text-qr-muted/50 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-medium text-qr-muted">Belum ada agenda rundown untuk event ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection