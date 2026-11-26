@extends('layouts.event-management')

@section('event-content')
@php
    $ticketTypes = $event->ticketTypes ?? collect();
    $booths      = $event->booths ?? collect();
    $rundowns    = $event->rundowns ?? collect();
    $orders      = $event->ticketOrders ?? collect();
@endphp

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <x-stat label="Jenis tiket" :value="$ticketTypes->count()" icon="ticket" />
    <x-stat label="Pesanan"     :value="$orders->count()"      icon="orders" />
    <x-stat label="Booth"       :value="$booths->count()"      icon="booth" />
    <x-stat label="Sesi rundown" :value="$rundowns->count()"   icon="clock" />
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <section class="card card-pad lg:col-span-2">
        <h2 class="font-extrabold text-brand-950">Tentang event</h2>
        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $event->description ?: 'Belum ada deskripsi. Tambahkan lewat tombol Edit.' }}</p>

        <dl class="mt-6 grid gap-4 border-t border-brand-100 pt-5 text-sm sm:grid-cols-2">
            <div><dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mulai</dt><dd class="mt-1 font-semibold text-brand-950"><x-date :value="$event->starts_at ?? null" /></dd></div>
            <div><dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Selesai</dt><dd class="mt-1 font-semibold text-brand-950"><x-date :value="$event->ends_at ?? null" /></dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Lokasi</dt><dd class="mt-1 font-semibold text-brand-950">{{ $event->venue ?: '—' }}</dd></div>
        </dl>
    </section>

    <aside class="space-y-4">
        <section class="card card-pad">
            <h2 class="font-extrabold text-brand-950">Aksi cepat</h2>
            <div class="mt-4 grid gap-2">
                @foreach([
                    ['events.ticket-types.create', 'Tambah jenis tiket', 'ticket'],
                    ['events.booths.create',       'Tambah booth',       'booth'],
                    ['events.rundowns.create',     'Tambah sesi rundown','clock'],
                    ['events.checkin.index',       'Buka check-in',      'qr'],
                ] as [$route, $label, $icon])
                    @continue(!Route::has($route))
                    <a href="{{ route($route, $event) }}" class="btn-secondary justify-start"><x-icon :name="$icon" class="size-4" /> {{ $label }}</a>
                @endforeach
            </div>
        </section>

        @if(Route::has('events.public.show') && !empty($event->slug))
        <section class="card card-pad bg-gradient-to-br from-brand-50 to-white">
            <h2 class="font-extrabold text-brand-950">Link publik</h2>
            <p class="mt-1 text-xs text-slate-500">Bagikan ke calon peserta.</p>
            <input readonly onclick="this.select()" value="{{ route('events.public.show', $event->slug) }}" class="input mt-3 text-xs">
        </section>
        @endif
    </aside>
</div>
@endsection
