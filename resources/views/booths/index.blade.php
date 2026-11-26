@extends('layouts.event-management')

@section('event-content')
<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="text-lg font-extrabold text-brand-950">Booth</h2>
        <p class="text-sm text-slate-500">Slot yang bisa dipesan vendor di event ini.</p>
    </div>
    <a href="{{ route('events.booths.create', $event) }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Tambah booth</a>
</div>

@if($booths->isEmpty())
    <div class="card"><x-empty icon="booth" title="Belum ada booth" text="Tambahkan booth agar vendor bisa mengajukan pemesanan.">
        <a href="{{ route('events.booths.create', $event) }}" class="btn-primary">Tambah booth</a>
    </x-empty></div>
@else
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($booths as $b)
            <article class="card card-pad transition hover:-translate-y-0.5 hover:shadow-soft">
                <div class="flex items-start justify-between gap-3">
                    <span class="grid size-11 place-items-center rounded-xl bg-brand-100 text-brand-700"><x-icon name="booth" class="size-5" /></span>
                    <x-badge :status="$b->status ?? null" />
                </div>
                <h3 class="mt-4 font-extrabold text-brand-950">{{ $b->name }}</h3>
                @if(!empty($b->location))<p class="mt-0.5 flex items-center gap-1 text-xs text-slate-500"><x-icon name="pin" class="size-3.5" /> {{ $b->location }}</p>@endif
                <p class="mt-3 text-lg font-extrabold text-brand-700"><x-money :value="$b->price ?? 0" /></p>
                <div class="mt-4 flex items-center gap-1 border-t border-brand-100 pt-3">
                    <a href="{{ route('events.booths.edit', [$event, $b]) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                    <x-delete-button :action="route('events.booths.destroy', [$event, $b])"><x-icon name="trash" class="size-4" /></x-delete-button>
                </div>
            </article>
        @endforeach
    </div>
    <div class="mt-4"><x-pager :items="$booths" /></div>
@endif
@endsection
