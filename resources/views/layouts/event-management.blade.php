@extends('layouts.app')

@section('title', $event->nama_event)

@section('content')
@php
    $tabs = [
        ['events.show',          'Ringkasan',     'grid',   'events.show'],
        ['ticket-types.index',   'Tiket',         'ticket', 'ticket-types.*'],
        ['orders.index',         'Pesanan',       'orders', 'orders.*'],
        ['booths.index',         'Booth',         'booth',  'booths.*'],
        ['booth-bookings.index', 'Booking Booth', 'inbox',  'booth-bookings.*'],
        ['rundowns.index',       'Rundown',       'clock',  'rundowns.*'],
        ['members.index',        'Tim',           'users',  'members.*'],
    ];
@endphp

{{-- Header event --}}
<div class="card card-pad">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-muted transition-colors duration-150 hover:text-ink">
                <x-icon name="back" class="size-3.5" /> Semua event
            </a>
            <div class="mt-2.5 flex flex-wrap items-center gap-3">
                <h1 class="page-title truncate">{{ $event->nama_event }}</h1>
                <x-badge :status="$event->status ?? null" />
            </div>
            <p class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-muted">
                <span class="inline-flex items-center gap-1.5"><x-icon name="calendar" class="size-4 text-ink" /> <x-date :value="$event->tanggal_mulai ?? null" /></span>
                @if(!empty($event->lokasi))<span class="inline-flex items-center gap-1.5"><x-icon name="pin" class="size-4 text-ink" /> {{ $event->lokasi }}</span>@endif
                @if(!empty($event->subdomain))<span class="font-mono text-xs text-muted">{{ $event->subdomain }}.{{ config('events.base_domain') }}</span>@endif
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            @if(Route::has('event.home') && !empty($event->subdomain))
                <a href="{{ route('event.home', ['event_subdomain' => $event->subdomain]) }}" target="_blank" class="btn-secondary btn-sm">
                    <x-icon name="external" class="size-3.5" /> Halaman publik
                </a>
            @endif
            <a href="{{ route('events.edit', $event) }}" class="btn-primary btn-sm">
                <x-icon name="pencil" class="size-3.5" /> Edit
            </a>
        </div>
    </div>

    {{-- Tab --}}
    <nav class="mt-6 -mb-5 flex gap-1 overflow-x-auto border-t border-line pt-3 sm:-mb-6" aria-label="Menu event">
        @foreach($tabs as [$route, $label, $icon, $pattern])
            @continue(!Route::has($route))
            @php($active = request()->routeIs($pattern))
            <a href="{{ route($route, $event) }}"
               class="inline-flex items-center gap-2 whitespace-nowrap border-b-2 px-3 pb-3 pt-1 text-sm font-medium transition-colors duration-150
                      {{ $active ? 'border-ink font-semibold text-ink' : 'border-transparent text-muted hover:text-ink' }}">
                <x-icon :name="$icon" class="size-4" /> {{ $label }}
            </a>
        @endforeach
    </nav>
</div>

<div class="mt-6">
    @yield('event-content')
</div>
@endsection
