@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $eventCount    = $eventCount    ?? 0;
    $pendingOrders = $pendingOrders ?? 0;
    $boothCount    = $boothCount    ?? 0;
    $latestEvents  = $latestEvents  ?? collect();
@endphp

<x-page-header :title="'Halo, ' . \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') . ' 👋'" subtitle="Ringkasan aktivitas event kamu hari ini.">
    <x-slot:actions>
        @if(Route::has('events.create'))
            <a href="{{ route('events.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Event baru</a>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    <x-stat label="Total event"      :value="$eventCount"    icon="calendar" hint="Milik kamu" />
    <x-stat label="Menunggu bayar"   :value="$pendingOrders" icon="orders"   hint="Pesanan tiket" />
    <x-stat label="Total booth"      :value="$boothCount"    icon="booth"    hint="Di semua event" />
</div>

<section class="card mt-8 overflow-hidden">
    <div class="flex items-center justify-between border-b border-brand-100 px-5 py-4">
        <h2 class="font-extrabold text-brand-950">Event terbaru</h2>
        @if(Route::has('events.index'))<a href="{{ route('events.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-800">Lihat semua →</a>@endif
    </div>

    @forelse($latestEvents as $event)
        <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 border-b border-brand-100/70 px-5 py-4 last:border-0 hover:bg-brand-50/50">
            <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 text-lg font-extrabold text-white">
                {{ strtoupper(mb_substr($event->name, 0, 1)) }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="truncate font-bold text-brand-950">{{ $event->name }}</p>
                <p class="truncate text-xs text-slate-500"><x-date :value="$event->starts_at ?? null" />@if(!empty($event->venue)) · {{ $event->venue }}@endif</p>
            </div>
            <x-badge :status="$event->status ?? null" />
        </a>
    @empty
        <x-empty icon="calendar" title="Belum ada event" text="Buat event pertamamu untuk mulai menjual tiket dan mengatur booth.">
            @if(Route::has('events.create'))<a href="{{ route('events.create') }}" class="btn-primary">Buat event</a>@endif
        </x-empty>
    @endforelse
</section>
@endsection
