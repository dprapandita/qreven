@extends('layouts.event-management')

@section('event-content')
<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Booth</h2>
        <p class="text-sm text-muted">Slot yang bisa dipesan vendor di event ini.</p>
    </div>
    <a href="{{ route('booths.create', $event) }}" class="btn-accent"><x-icon name="plus" class="size-4" /> Tambah booth</a>
</div>

@if($booths->isEmpty())
    <div class="card"><x-empty icon="booth" title="Belum ada booth" text="Tambahkan booth agar vendor bisa mengajukan pemesanan.">
        <a href="{{ route('booths.create', $event) }}" class="btn-accent">Tambah booth</a>
    </x-empty></div>
@else
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($booths as $b)
            <article class="card card-pad transition-colors duration-150 hover:border-ink">
                <div class="flex items-start justify-between gap-3">
                    <span class="grid size-9 place-items-center rounded border border-line bg-canvas text-ink"><x-icon name="booth" class="size-4" /></span>
                    <x-badge :status="$b->status ?? null" />
                </div>
                <h3 class="font-display mt-4 font-bold text-ink">{{ $b->nama_booth }}</h3>
                @if(!empty($b->deskripsi))<p class="mt-1 line-clamp-2 text-xs text-muted">{{ $b->deskripsi }}</p>@endif
                <p class="font-mono mt-3 text-base font-bold text-ink"><x-money :value="$b->harga ?? 0" /></p>
                <div class="mt-4 flex items-center gap-1 border-t border-line pt-3">
                    <a href="{{ route('booths.edit', [$event, $b]) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                    <x-delete-button :action="route('booths.destroy', [$event, $b])"><x-icon name="trash" class="size-4" /></x-delete-button>
                </div>
            </article>
        @endforeach
    </div>
    <div class="mt-4"><x-pager :items="$booths" /></div>
@endif
@endsection
