@extends('layouts.event-management')

@section('event-content')
@php
    $ticketTypes = $event->ticketTypes ?? collect();
    $booths      = $event->booths ?? collect();
    $rundowns    = $event->rundowns ?? collect();
    $orders      = $event->ticketOrders ?? collect();
@endphp

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-stat label="Jenis tiket"  :value="$ticketTypes->count()" icon="ticket" />
    <x-stat label="Pesanan"      :value="$orders->count()"      icon="orders" />
    <x-stat label="Booth"        :value="$booths->count()"      icon="booth" />
    <x-stat label="Sesi rundown" :value="$rundowns->count()"    icon="clock" />
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <section class="card card-pad lg:col-span-2">
        <h2 class="font-display text-base font-bold text-ink">Tentang event</h2>
        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-muted">{{ $event->deskripsi ?: 'Belum ada deskripsi. Tambahkan lewat tombol Edit.' }}</p>

        <dl class="mt-6 grid gap-4 border-t border-line pt-5 text-sm sm:grid-cols-2">
            <div><dt class="eyebrow">Mulai</dt><dd class="mt-1 font-medium text-ink"><x-date :value="$event->tanggal_mulai ?? null" /></dd></div>
            <div><dt class="eyebrow">Selesai</dt><dd class="mt-1 font-medium text-ink"><x-date :value="$event->tanggal_selesai ?? null" /></dd></div>
            <div class="sm:col-span-2"><dt class="eyebrow">Lokasi</dt><dd class="mt-1 font-medium text-ink">{{ $event->lokasi ?: '—' }}</dd></div>
        </dl>
    </section>

    <aside class="space-y-4">
        <section class="card card-pad">
            <h2 class="font-display text-base font-bold text-ink">Aksi cepat</h2>
            <div class="mt-4 grid gap-2">
                @foreach([
                    ['ticket-types.create', [$event], 'Tambah jenis tiket',  'ticket'],
                    ['booths.create',       [$event], 'Tambah booth',        'booth'],
                    ['rundowns.create',     [$event], 'Tambah sesi rundown', 'clock'],
                    ['checkin',             [],       'Buka check-in',       'qr'],
                ] as [$route, $params, $label, $icon])
                    @continue(!Route::has($route))
                    <a href="{{ route($route, $params) }}" class="btn-secondary justify-start"><x-icon :name="$icon" class="size-4" /> {{ $label }}</a>
                @endforeach
            </div>
        </section>

        @if(Route::has('event.home') && !empty($event->subdomain))
        <section class="card card-pad bg-surface">
            <h2 class="font-display text-base font-bold text-ink">Link publik</h2>
            <p class="mt-1 text-xs text-muted">Bagikan halaman event ke calon peserta.</p>
            <input readonly onclick="this.select()" value="{{ route('event.home', ['event_subdomain' => $event->subdomain]) }}" class="input mt-3 font-mono text-xs">
        </section>
        @endif
    </aside>
</div>
@endsection
