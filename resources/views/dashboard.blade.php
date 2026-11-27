@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $eventCount        = $eventCount ?? 0;
    $pendingOrderCount = $pendingOrderCount ?? 0;
    $boothCount        = $boothCount ?? 0;
    $recentEvents      = $recentEvents ?? collect();
@endphp

<x-page-header :title="'Halo, ' . \Illuminate\Support\Str::of(auth()->user()->name)->before(' ')" subtitle="Ringkasan operasional event kamu hari ini.">
    <x-slot:actions>
        @if(Route::has('events.create'))
            <a href="{{ route('events.create') }}" class="btn-accent"><x-icon name="plus" class="size-4" /> Event baru</a>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <x-stat label="Total event"    :value="$eventCount"        icon="calendar" hint="Event milik kamu" />
    <x-stat label="Menunggu bayar" :value="$pendingOrderCount" icon="orders"   hint="Pesanan tiket pending" />
    <x-stat label="Total booth"    :value="$boothCount"        icon="booth"    hint="Di semua event" />
</div>

<section class="card mt-8 overflow-hidden">
    <div class="flex items-center justify-between border-b border-line px-5 py-4">
        <h2 class="font-display text-base font-bold text-ink">Event terbaru</h2>
        @if(Route::has('events.index'))
            <a href="{{ route('events.index') }}" class="text-xs font-semibold text-ink underline underline-offset-4 hover:text-muted">Lihat semua →</a>
        @endif
    </div>

    @forelse($recentEvents as $event)
        <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 border-b border-line px-5 py-4 transition-colors duration-150 last:border-0 hover:bg-canvas/60">
            <span class="font-display grid size-10 shrink-0 place-items-center rounded border border-[#2C3028] bg-[#191B18] text-sm font-bold text-[#D9F478]">
                {{ strtoupper(mb_substr($event->nama_event, 0, 1)) }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="truncate font-display text-sm font-bold text-ink">{{ $event->nama_event }}</p>
                <p class="mt-0.5 truncate text-xs text-muted">
                    <x-date :value="$event->tanggal_mulai ?? null" />@if(!empty($event->lokasi)) · {{ $event->lokasi }}@endif
                </p>
            </div>
            <x-badge :status="$event->status ?? null" />
        </a>
    @empty
        <x-empty icon="calendar" title="Belum ada event" text="Buat event pertamamu untuk mulai menjual tiket dan mengatur booth.">
            @if(Route::has('events.create'))<a href="{{ route('events.create') }}" class="btn-accent">Buat event</a>@endif
        </x-empty>
    @endforelse
</section>
@endsection
