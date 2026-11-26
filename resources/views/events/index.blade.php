@extends('layouts.app')
@section('title', 'Event Saya')

@section('content')
<x-page-header title="Event Saya" subtitle="Semua event yang kamu kelola.">
    <x-slot:actions>
        <a href="{{ route('events.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Event baru</a>
    </x-slot:actions>
</x-page-header>

@if($events->isEmpty())
    <div class="card">
        <x-empty icon="calendar" title="Belum ada event" text="Buat event pertamamu, lalu tambahkan tiket, booth, dan rundown.">
            <a href="{{ route('events.create') }}" class="btn-primary">Buat event</a>
        </x-empty>
    </div>
@else
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($events as $event)
            <article class="card group flex flex-col overflow-hidden transition hover:-translate-y-1 hover:shadow-soft">
                <a href="{{ route('events.show', $event) }}" class="gradient-brand relative block h-28 overflow-hidden">
                    <div class="absolute -right-6 -top-8 size-32 rounded-full bg-accent-500/40 blur-2xl"></div>
                    <span class="absolute bottom-3 left-5 text-4xl font-extrabold text-white/90">{{ strtoupper(mb_substr($event->name, 0, 1)) }}</span>
                    <x-badge :status="$event->status ?? null" class="absolute! right-3 top-3 bg-white/90!" />
                </a>
                <div class="flex flex-1 flex-col p-5">
                    <h3 class="line-clamp-1 text-base font-extrabold text-brand-950">
                        <a href="{{ route('events.show', $event) }}" class="hover:text-brand-600">{{ $event->name }}</a>
                    </h3>
                    <dl class="mt-3 space-y-1.5 text-sm text-slate-500">
                        <div class="flex items-center gap-2"><x-icon name="calendar" class="size-4 text-brand-500" /> <x-date :value="$event->starts_at ?? null" /></div>
                        @if(!empty($event->venue))<div class="flex items-center gap-2"><x-icon name="pin" class="size-4 text-brand-500" /> <span class="truncate">{{ $event->venue }}</span></div>@endif
                    </dl>
                    <div class="mt-5 flex items-center gap-2 border-t border-brand-100 pt-4">
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
