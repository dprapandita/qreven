@extends('layouts.app')

@section('title', $event->name)

@section('content')
@php
    $tabs = [
        ['events.show',                 'Ringkasan',     'grid',   'events.show'],
        ['events.ticket-types.index',   'Tiket',         'ticket', 'events.ticket-types.*'],
        ['events.ticket-orders.index',  'Pesanan',       'orders', 'events.ticket-orders.*'],
        ['events.booths.index',         'Booth',         'booth',  'events.booths.*'],
        ['events.booth-bookings.index', 'Booking Booth', 'users',  'events.booth-bookings.*'],
        ['events.rundowns.index',       'Rundown',       'clock',  'events.rundowns.*'],
        ['events.checkin.index',        'Check-in',      'qr',     'events.checkin.*'],
    ];
@endphp

{{-- Header event --}}
<div class="gradient-brand relative overflow-hidden rounded-3xl p-6 text-white shadow-soft sm:p-8">
    <div class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full bg-accent-500/30 blur-3xl"></div>
    <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-brand-200 hover:text-white">
                <x-icon name="back" class="size-3.5" /> Semua event
            </a>
            <div class="mt-2 flex flex-wrap items-center gap-3">
                <h1 class="truncate text-2xl font-extrabold sm:text-3xl">{{ $event->name }}</h1>
                <x-badge :status="$event->status ?? null" class="bg-white/15! text-white! ring-white/30!" />
            </div>
            <p class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-brand-100">
                <span class="inline-flex items-center gap-1.5"><x-icon name="calendar" class="size-4" /> <x-date :value="$event->starts_at ?? null" /></span>
                @if(!empty($event->venue))<span class="inline-flex items-center gap-1.5"><x-icon name="pin" class="size-4" /> {{ $event->venue }}</span>@endif
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            @if(Route::has('events.public.show') && !empty($event->slug))
                <a href="{{ route('events.public.show', $event->slug) }}" target="_blank" class="btn bg-white/15 text-white ring-1 ring-white/25 hover:bg-white/25">
                    <x-icon name="external" class="size-4" /> Halaman publik
                </a>
            @endif
            <a href="{{ route('events.edit', $event) }}" class="btn bg-white text-brand-700 hover:bg-brand-50"><x-icon name="pencil" class="size-4" /> Edit</a>
        </div>
    </div>
</div>

{{-- Tab --}}
<nav class="-mx-1 mt-6 overflow-x-auto px-1 pb-1">
    <div class="inline-flex min-w-full gap-1 rounded-2xl border border-brand-100 bg-white p-1.5 shadow-sm">
        @foreach($tabs as [$route, $label, $icon, $pattern])
            @continue(!Route::has($route))
            <a href="{{ route($route, $event) }}"
               class="inline-flex items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2 text-sm font-semibold transition
                      {{ request()->routeIs($pattern) ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:bg-brand-50 hover:text-brand-700' }}">
                <x-icon :name="$icon" class="size-4" /> {{ $label }}
            </a>
        @endforeach
    </div>
</nav>

<div class="mt-6">
    @yield('event-content')
</div>
@endsection
