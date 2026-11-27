<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->nama_event }} · {{ config('app.name', 'Qreven') }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags((string) ($event->deskripsi ?? '')), 150) }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,600,700|jetbrains-mono:400,500,600&display=swap" rel="stylesheet">
    <x-theme-init />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.event-style')
</head>
<body class="min-h-screen bg-canvas text-ink">
@php
    $ticketTypes = ($event->ticketTypes ?? collect())->sortBy('harga');
    $rundowns    = ($event->rundowns ?? collect())->sortBy('waktu_mulai');
    $booths      = $event->booths ?? collect();
@endphp

<header class="border-b border-line bg-surface">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
        <a href="{{ route('event.home', ['event_subdomain' => $event->subdomain]) }}" class="flex items-center gap-2.5">
            <span class="grid size-8 place-items-center rounded border border-ink bg-accent text-ink">
                <x-icon name="qr" class="size-4" />
            </span>
            <span class="font-display text-base font-bold tracking-tight text-ink">{{ config('app.name', 'Qreven') }}</span>
        </a>
        <div class="flex items-center gap-2">
            <x-theme-toggle />
            <a href="{{ route('event.tickets', ['event_subdomain' => $event->subdomain]) }}" class="btn-accent btn-sm">
                <x-icon name="ticket" class="size-4" /> Lihat Tiket
            </a>
        </div>
    </div>
</header>

{{-- Editorial Event Masthead --}}
<section class="panel-dark border-b">
    <div class="mx-auto max-w-6xl px-6 py-12 sm:py-16">
        <div class="flex flex-wrap items-center gap-2.5 font-mono text-xs text-white/80">
            <span class="inline-flex items-center gap-1.5 rounded border border-white/20 px-2.5 py-1">
                <x-icon name="calendar" class="size-3.5 text-[#D9F478]" />
                <x-date :value="$event->tanggal_mulai ?? null" format="l, d F Y" />
            </span>
            <span class="inline-flex items-center gap-1.5 rounded border border-white/20 px-2.5 py-1">
                <x-icon name="clock" class="size-3.5 text-[#D9F478]" />
                <x-date :value="$event->tanggal_mulai ?? null" format="H:i" /> {{ $event->timezone ?? 'WIB' }}
            </span>
            @if(!empty($event->lokasi))
                <span class="inline-flex items-center gap-1.5 rounded border border-white/20 px-2.5 py-1">
                    <x-icon name="pin" class="size-3.5 text-[#D9F478]" />
                    {{ $event->lokasi }}
                </span>
            @endif
        </div>

        <h1 class="font-display mt-6 max-w-4xl text-4xl font-bold leading-[1.08] tracking-tight text-white sm:text-6xl">
            {{ $event->nama_event }}
        </h1>

        @if(!empty($event->poster))
            <div class="mt-8 overflow-hidden rounded-md border border-white/20">
                <img src="{{ $event->poster }}" alt="{{ $event->nama_event }}" class="max-h-96 w-full object-cover">
            </div>
        @endif
    </div>
</section>

<main class="mx-auto grid max-w-6xl gap-10 px-6 py-12 lg:grid-cols-3">
    <div class="space-y-12 lg:col-span-2">
        <section class="card card-pad">
            <p class="eyebrow">Informasi</p>
            <h2 class="font-display mt-1 text-xl font-bold text-ink">Tentang event</h2>
            <p class="mt-4 whitespace-pre-line text-sm leading-relaxed text-muted sm:text-base">{{ $event->deskripsi ?: 'Detail event segera diumumkan.' }}</p>
        </section>

        @if($rundowns->isNotEmpty())
        <section class="card card-pad">
            <p class="eyebrow">Jadwal</p>
            <h2 class="font-display mt-1 text-xl font-bold text-ink">Rundown</h2>
            <ol class="ev-timeline mt-6 space-y-6">
                @foreach($rundowns as $r)
                    <li class="relative">
                        <span class="ev-dot"></span>
                        <p class="font-mono text-xs font-semibold uppercase tracking-wider text-muted">
                            <x-date :value="$r->waktu_mulai ?? null" format="H:i" />@if(!empty($r->waktu_selesai)) – <x-date :value="$r->waktu_selesai" format="H:i" />@endif
                        </p>
                        <h3 class="font-display mt-1 text-base font-bold text-ink">{{ $r->nama_kegiatan }}</h3>
                        @if(!empty($r->deskripsi))<p class="mt-1 text-sm text-muted">{{ $r->deskripsi }}</p>@endif
                    </li>
                @endforeach
            </ol>
        </section>
        @endif

        @if($booths->isNotEmpty())
        <section class="card card-pad">
            <p class="eyebrow">Pameran</p>
            <h2 class="font-display mt-1 text-xl font-bold text-ink">Booth</h2>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach($booths as $b)
                    <div class="rounded-md border border-line bg-canvas/50 p-4">
                        <p class="font-display font-bold text-ink">{{ $b->nama_booth }}</p>
                        @if(!empty($b->deskripsi))<p class="mt-1 text-xs text-muted">{{ $b->deskripsi }}</p>@endif
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    {{-- Sticky Ticket Summary --}}
    <aside>
        <div class="card-brutal card-pad lg:sticky lg:top-6">
            <div class="flex items-center justify-between border-b border-line pb-3">
                <h2 class="font-display text-lg font-bold text-ink">Tiket</h2>
                <span class="font-mono text-xs text-muted">{{ $ticketTypes->count() }} kategori</span>
            </div>

            <div class="mt-4 space-y-3">
                @forelse($ticketTypes as $t)
                    <div class="rounded-md border border-line bg-canvas/60 p-3.5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-display font-bold text-ink">{{ $t->nama_tiket }}</p>
                                <p class="mt-0.5 font-mono text-xs text-muted">
                                    {{ $t->kuota > 0 ? "Tersisa {$t->kuota}" : 'Habis' }}
                                </p>
                            </div>
                            <p class="font-display shrink-0 text-sm font-bold text-ink"><x-money :value="$t->harga ?? 0" /></p>
                        </div>
                    </div>
                @empty
                    <p class="rounded-md border border-line bg-canvas p-4 text-center text-sm text-muted">Tiket belum tersedia.</p>
                @endforelse
            </div>

            <a href="{{ route('event.tickets', ['event_subdomain' => $event->subdomain]) }}" class="btn-accent mt-5 w-full py-2.5">
                Lihat semua tiket
            </a>
        </div>
    </aside>
</main>

<footer class="border-t border-line bg-surface py-8 text-center font-mono text-xs text-muted">
    Dikelola dengan {{ config('app.name', 'Qreven') }}
</footer>
</body>
</html>
