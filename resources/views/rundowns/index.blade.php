@extends('layouts.event-management')

@section('event-content')
<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="text-lg font-extrabold text-brand-950">Rundown acara</h2>
        <p class="text-sm text-slate-500">Urutan sesi dari awal sampai akhir.</p>
    </div>
    <a href="{{ route('events.rundowns.create', $event) }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Tambah sesi</a>
</div>

<div class="card overflow-hidden">
    @forelse($rundowns as $r)
        <div class="flex flex-col gap-3 border-b border-brand-100/70 px-5 py-4 last:border-0 sm:flex-row sm:items-center">
            <div class="w-40 shrink-0">
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-brand-100 px-2.5 py-1 text-xs font-bold text-brand-700">
                    <x-icon name="clock" class="size-3.5" />
                    <x-date :value="$r->starts_at ?? null" format="H:i" />@if(!empty($r->ends_at)) – <x-date :value="$r->ends_at" format="H:i" />@endif
                </span>
                <p class="mt-1 text-xs text-slate-400"><x-date :value="$r->starts_at ?? null" format="d M Y" /></p>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-brand-950">{{ $r->title }}</p>
                @if(!empty($r->speaker))<p class="text-sm text-slate-500">{{ $r->speaker }}</p>@endif
            </div>
            <div class="flex shrink-0 items-center">
                <a href="{{ route('events.rundowns.edit', [$event, $r]) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                <x-delete-button :action="route('events.rundowns.destroy', [$event, $r])"><x-icon name="trash" class="size-4" /></x-delete-button>
            </div>
        </div>
    @empty
        <x-empty icon="clock" title="Rundown masih kosong" text="Susun sesi acara supaya peserta tahu jadwalnya.">
            <a href="{{ route('events.rundowns.create', $event) }}" class="btn-primary">Tambah sesi</a>
        </x-empty>
    @endforelse
    <x-pager :items="$rundowns" />
</div>
@endsection
