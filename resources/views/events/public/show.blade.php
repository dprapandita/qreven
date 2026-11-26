<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->name }} · {{ config('app.name', 'Qreven') }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags((string) ($event->description ?? '')), 150) }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.event-style', ['accent' => $event->accent_color ?? null])
</head>
<body class="bg-white">
@php
    $ticketTypes = $event->ticketTypes ?? collect();
    $rundowns    = ($event->rundowns ?? collect())->sortBy('starts_at');
    $booths      = $event->booths ?? collect();
@endphp

{{-- Hero --}}
<header class="ev-hero relative overflow-hidden text-white">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
        <span class="flex items-center gap-2 text-sm font-bold">
            <span class="grid size-8 place-items-center rounded-lg bg-white/15 ring-1 ring-white/25"><x-icon name="qr" class="size-4" /></span>
            {{ config('app.name', 'Qreven') }}
        </span>
    </div>
    <div class="mx-auto max-w-6xl px-6 pb-20 pt-10 sm:pt-16">
        <div class="flex flex-wrap gap-2">
            <span class="ev-chip"><x-icon name="calendar" class="size-4" /> <x-date :value="$event->starts_at ?? null" format="l, d F Y" /></span>
            <span class="ev-chip"><x-icon name="clock" class="size-4" /> <x-date :value="$event->starts_at ?? null" format="H:i" /> WIB</span>
            @if(!empty($event->venue))<span class="ev-chip"><x-icon name="pin" class="size-4" /> {{ $event->venue }}</span>@endif
        </div>
        <h1 class="mt-6 max-w-3xl text-4xl font-extrabold leading-tight tracking-tight sm:text-6xl">{{ $event->name }}</h1>
    </div>
</header>

<main class="mx-auto -mt-10 grid max-w-6xl gap-8 px-6 pb-20 lg:grid-cols-3">

    <div class="space-y-12 lg:col-span-2 lg:pt-14">
        <section>
            <h2 class="ev-section-title">Tentang event</h2>
            <p class="mt-3 whitespace-pre-line leading-relaxed text-slate-600">{{ $event->description ?: 'Detail event segera diumumkan.' }}</p>
        </section>

        @if($rundowns->isNotEmpty())
        <section>
            <h2 class="ev-section-title">Rundown</h2>
            <ol class="ev-timeline mt-6 space-y-6">
                @foreach($rundowns as $r)
                    <li class="relative">
                        <span class="ev-dot"></span>
                        <p class="text-xs font-bold uppercase tracking-wider" style="color:var(--ev-accent)">
                            <x-date :value="$r->starts_at ?? null" format="H:i" />@if(!empty($r->ends_at)) – <x-date :value="$r->ends_at" format="H:i" />@endif
                        </p>
                        <h3 class="mt-0.5 font-extrabold text-brand-950">{{ $r->title }}</h3>
                        @if(!empty($r->speaker))<p class="text-sm font-medium text-slate-500">{{ $r->speaker }}</p>@endif
                        @if(!empty($r->description))<p class="mt-1 text-sm text-slate-600">{{ $r->description }}</p>@endif
                    </li>
                @endforeach
            </ol>
        </section>
        @endif

        @if($booths->isNotEmpty())
        <section>
            <h2 class="ev-section-title">Booth</h2>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach($booths as $b)
                    <div class="rounded-2xl border border-brand-100 p-4">
                        <p class="font-bold text-brand-950">{{ $b->name }}</p>
                        @if(!empty($b->location))<p class="mt-0.5 text-xs text-slate-500">{{ $b->location }}</p>@endif
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    {{-- Kartu tiket --}}
    <aside class="lg:pt-0">
        <div class="card card-pad shadow-soft lg:sticky lg:top-6">
            <h2 class="text-lg font-extrabold text-brand-950">Tiket</h2>
            <div class="mt-4">
                @forelse($ticketTypes as $t)
                    <div class="ev-ticket p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-bold text-brand-950">{{ $t->name }}</p>
                                @if(!empty($t->description))<p class="mt-0.5 line-clamp-2 text-xs text-slate-500">{{ $t->description }}</p>@endif
                            </div>
                            <p class="shrink-0 text-sm font-extrabold" style="color:var(--ev-accent)"><x-money :value="$t->price ?? 0" /></p>
                        </div>
                    </div>
                @empty
                    <p class="rounded-xl bg-brand-50 p-4 text-center text-sm text-slate-500">Tiket belum tersedia.</p>
                @endforelse
            </div>
            <button type="button" disabled class="btn-primary mt-5 w-full py-3" title="Pemesanan tiket segera hadir">Pesan tiket · segera hadir</button>
        </div>
    </aside>
</main>

<footer class="border-t border-brand-100 py-8 text-center text-xs text-slate-500">Dikelola dengan {{ config('app.name', 'Qreven') }}</footer>
</body>
</html>
