@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="space-y-12 pb-16">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-center justify-between rounded-xl border border-qr-success/30 bg-qr-success/10 px-4 py-3 text-sm text-qr-success backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-qr-success/70 hover:text-qr-success transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="flex items-center justify-between rounded-xl border border-qr-danger/30 bg-qr-danger/10 px-4 py-3 text-sm text-qr-danger backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-qr-danger/70 hover:text-qr-danger transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Hero Section --}}
    <section class="relative overflow-hidden rounded-3xl border border-qr-border bg-gradient-to-br from-qr-surface via-qr-card/50 to-qr-bg p-8 sm:p-12 lg:p-16 shadow-2xl">
        {{-- Ambient violet background glow --}}
        <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-qr-accent/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-qr-accent-dark/10 blur-3xl"></div>

        <div class="relative z-10 max-w-3xl">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black uppercase tracking-tight text-qr-text leading-tight">
                EVERY EVENT HAS ITS<br>
                <span class="italic text-qr-accent">STORY.</span>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-qr-muted max-w-xl">
                Find events, buy tickets — all in one place.
            </p>
            <div class="mt-8">
                <a href="#events-section" class="inline-flex items-center gap-2 rounded-xl border border-qr-accent px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-qr-accent transition-all duration-300 hover:bg-qr-accent hover:text-white hover:shadow-[0_0_25px_rgba(139,92,246,0.35)] hover:scale-105 active:scale-95">
                    GO TO EVENTS &rarr;
                </a>
            </div>
        </div>
    </section>

    {{-- Events Section --}}
    <section id="events-section" class="relative">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold uppercase tracking-widest text-qr-text flex items-center gap-3">
                    <span class="h-2.5 w-2.5 rounded-full bg-qr-accent shadow-[0_0_10px_rgba(139,92,246,0.8)]"></span>
                    WHAT'S HAPPENING TODAY
                </h2>
            </div>
        </div>

        {{-- Events Grid: 3 columns on desktop, 1 on mobile --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($events as $event)
                <div class="group relative flex flex-col rounded-2xl border border-qr-border bg-qr-card overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02] hover:border-qr-accent hover:shadow-[0_0_25px_rgba(139,92,246,0.25)]">
                    {{-- Gradient top area (violet tint) --}}
                    <div class="relative h-48 w-full overflow-hidden bg-gradient-to-br from-qr-accent/25 via-qr-surface to-qr-card">
                        @if (!empty($event->poster))
                            <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->nama_event }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-qr-card via-qr-card/30 to-qr-accent/10"></div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-qr-accent/20 via-qr-surface to-qr-card flex items-center justify-center">
                                <div class="flex flex-col items-center gap-1 text-qr-accent/40 group-hover:text-qr-accent/60 transition-colors">
                                    <span class="text-4xl font-black">✦</span>
                                    <span class="text-[11px] uppercase tracking-widest font-semibold">Qreven Event</span>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-qr-card via-transparent to-transparent"></div>
                        @endif

                        {{-- Status Badge with colored dot --}}
                        <div class="absolute top-3.5 right-3.5 z-10">
                            @php
                                $status = strtolower($event->status ?? 'upcoming');
                                $isSuccess = in_array($status, ['aktif', 'active', 'published', 'open', 'ongoing', 'live']);
                                $isDanger = in_array($status, ['selesai', 'ended', 'closed', 'cancelled']);
                                
                                $badgeStyle = $isSuccess 
                                    ? 'border-qr-success/30 bg-qr-surface/90 text-qr-success' 
                                    : ($isDanger 
                                        ? 'border-qr-danger/30 bg-qr-surface/90 text-qr-danger' 
                                        : 'border-qr-warning/30 bg-qr-surface/90 text-qr-warning');
                                $dotColor = $isSuccess ? 'bg-qr-success' : ($isDanger ? 'bg-qr-danger' : 'bg-qr-warning');
                            @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold uppercase tracking-wider backdrop-blur-md {{ $badgeStyle }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $dotColor }}"></span>
                                {{ $event->status ?? 'Upcoming' }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-lg font-bold text-qr-text group-hover:text-qr-accent-light transition-colors line-clamp-1">
                            {{ $event->nama_event }}
                        </h3>

                        @if (!empty($event->deskripsi))
                            <p class="mt-2 text-sm text-qr-muted line-clamp-2 leading-relaxed">
                                {{ $event->deskripsi }}
                            </p>
                        @endif

                        <div class="mt-4 space-y-2.5 border-t border-qr-border/60 pt-4 text-xs text-qr-muted">
                            {{-- Location --}}
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-qr-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $event->lokasi }}</span>
                            </div>

                            {{-- Date Range --}}
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-qr-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate">
                                    @if (!empty($event->tanggal_mulai))
                                        {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }}
                                    @endif
                                    @if (!empty($event->tanggal_selesai))
                                        &ndash; {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Action Link --}}
                        <div class="mt-5 pt-3">
                            <a href="{{ Route::has('events.show') ? route('events.show', $event->id) : '#' }}" class="flex w-full items-center justify-center gap-2 rounded-xl border border-qr-border bg-qr-surface py-2.5 text-xs font-semibold uppercase tracking-wider text-qr-text transition-all duration-200 hover:border-qr-accent hover:bg-qr-accent hover:text-white">
                                <span>View Details</span>
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-3xl border border-dashed border-qr-border bg-qr-surface/30 p-12 text-center backdrop-blur-sm sm:p-16">
                    <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-qr-border bg-qr-card text-qr-accent shadow-[0_0_20px_rgba(139,92,246,0.15)]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-wider text-qr-text">No Events Found</h3>
                    <p class="mt-2 max-w-md text-sm text-qr-muted leading-relaxed">
                        There are no events happening today or scheduled yet. Click below or use the floating button to create the first event.
                    </p>
                    <a href="{{ Route::has('events.create') ? route('events.create') : '#' }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-qr-accent px-6 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-qr-accent/30 transition-all duration-300 hover:bg-qr-accent-dark hover:scale-105 active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Event
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Floating Action Button (FAB) --}}
    <a href="{{ Route::has('events.create') ? route('events.create') : '#' }}" 
       title="Add New Event"
       class="fixed bottom-8 right-8 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-qr-accent text-white shadow-xl shadow-qr-accent/40 transition-all duration-300 hover:scale-110 hover:bg-qr-accent-dark hover:shadow-[0_0_25px_rgba(139,92,246,0.6)] active:scale-95 focus:outline-none focus:ring-4 focus:ring-qr-accent/30">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
    </a>
</div>
@endsection