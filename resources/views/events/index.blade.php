@extends('layouts.app')
@section('title', 'Event Saya')

@section('content')
<x-page-header title="Event Saya" subtitle="Semua event yang kamu kelola.">
    <x-slot:actions>
        <a href="{{ route('events.create') }}" class="btn-accent"><x-icon name="plus" class="size-4" /> Event baru</a>
    </x-slot:actions>
</x-page-header>

@if($events->isEmpty())
    <div class="card">
        <x-empty icon="calendar" title="Belum ada event" text="Buat event pertamamu, lalu tambahkan tiket, booth, dan rundown.">
            <a href="{{ route('events.create') }}" class="btn-accent">Buat event</a>
        </x-empty>
    </div>
@else
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($events as $event)
            <article class="card flex flex-col overflow-hidden transition-colors duration-150 hover:border-ink">
                <a href="{{ route('events.show', $event) }}" class="panel-dark flex items-center justify-between border-b px-5 py-4">
                    <span class="font-display text-2xl font-bold text-[#D9F478]">{{ strtoupper(mb_substr($event->nama_event, 0, 1)) }}</span>
                    <x-badge :status="$event->status ?? null" />
                </a>
                <div class="flex flex-1 flex-col p-5">
                    <h3 class="font-display line-clamp-1 text-base font-bold text-ink">
                        <a href="{{ route('events.show', $event) }}" class="hover:underline">{{ $event->nama_event }}</a>
                    </h3>
                    @if(!empty($event->subdomain))
                        <p class="mt-0.5 font-mono text-xs text-muted">{{ $event->subdomain }}.{{ config('events.base_domain') }}</p>
                    @endif
                    <dl class="mt-3 space-y-1.5 text-xs text-muted">
                        <div class="flex items-center gap-2"><x-icon name="calendar" class="size-3.5 text-ink" /> <x-date :value="$event->tanggal_mulai ?? null" /></div>
                        @if(!empty($event->lokasi))<div class="flex items-center gap-2"><x-icon name="pin" class="size-3.5 text-ink" /> <span class="truncate">{{ $event->lokasi }}</span></div>@endif
                    </dl>
                    <div class="mt-5 flex items-center gap-2 border-t border-line pt-4">
                        <a href="{{ route('events.show', $event) }}" class="btn-secondary btn-sm flex-1">Kelola</a>
                        <a href="{{ route('events.edit', $event) }}" class="btn-ghost btn-sm" title="Edit"><x-icon name="pencil" class="size-4" /></a>
                        <x-delete-button :action="route('events.destroy', $event)" confirm="Hapus event ini beserta tiket, booth, dan rundown-nya?"><x-icon name="trash" class="size-4" /></x-delete-button>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
    @if($events instanceof \Illuminate\Contracts\Pagination\Paginator)<div class="mt-6">{{ $events->links() }}</div>@endif
@endif
@endsection
