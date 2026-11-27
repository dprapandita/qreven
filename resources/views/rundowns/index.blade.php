@extends('layouts.event-management')

@section('event-content')
<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Rundown acara</h2>
        <p class="text-sm text-muted">Urutan sesi dari awal sampai akhir.</p>
    </div>
    <a href="{{ route('rundowns.create', $event) }}" class="btn-accent"><x-icon name="plus" class="size-4" /> Tambah sesi</a>
</div>

<div class="card overflow-hidden">
    @forelse($rundowns as $r)
        <div class="flex flex-col gap-3 border-b border-line px-5 py-4 last:border-0 sm:flex-row sm:items-center">
            <div class="w-44 shrink-0">
                <span class="inline-flex items-center gap-1.5 rounded border border-line bg-canvas px-2.5 py-1 font-mono text-xs font-semibold text-ink">
                    <x-icon name="clock" class="size-3.5" />
                    <x-date :value="$r->waktu_mulai ?? null" format="H:i" />@if(!empty($r->waktu_selesai)) – <x-date :value="$r->waktu_selesai" format="H:i" />@endif
                </span>
                <p class="mt-1 font-mono text-[11px] text-muted"><x-date :value="$r->waktu_mulai ?? null" format="d M Y" /></p>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-display font-bold text-ink">{{ $r->nama_kegiatan }}</p>
                @if(!empty($r->deskripsi))<p class="mt-0.5 text-sm text-muted">{{ $r->deskripsi }}</p>@endif
            </div>
            <div class="flex shrink-0 items-center">
                <a href="{{ route('rundowns.edit', [$event, $r]) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                <x-delete-button :action="route('rundowns.destroy', [$event, $r])"><x-icon name="trash" class="size-4" /></x-delete-button>
            </div>
        </div>
    @empty
        <x-empty icon="clock" title="Rundown masih kosong" text="Susun sesi acara supaya peserta tahu jadwalnya.">
            <a href="{{ route('rundowns.create', $event) }}" class="btn-accent">Tambah sesi</a>
        </x-empty>
    @endforelse
    <x-pager :items="$rundowns" />
</div>
@endsection
