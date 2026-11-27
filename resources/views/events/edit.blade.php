@extends('layouts.app')
@section('title', 'Edit ' . $event->nama_event)

@section('content')
<x-page-header title="Edit event" :subtitle="$event->nama_event" :back="route('events.show', $event)" />

<form method="POST" action="{{ route('events.update', $event) }}" class="card card-pad max-w-3xl">
    @csrf @method('PUT')
    @include('events._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-line pt-6">
        <a href="{{ route('events.show', $event) }}" class="btn-secondary">Batal</a>
        <button type="submit" class="btn-accent">Simpan perubahan</button>
    </div>
</form>

<div class="card card-pad mt-8 max-w-3xl border-error/40">
    <h2 class="font-display text-base font-bold text-error">Zona berbahaya</h2>
    <p class="mt-1 text-sm text-muted">Menghapus event akan ikut menghapus tiket, booth, dan rundown miliknya.</p>
    <div class="mt-4">
        <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini secara permanen?')">
            @csrf @method('DELETE')
            <button class="btn-danger btn-sm"><x-icon name="trash" class="size-4" /> Hapus event</button>
        </form>
    </div>
</div>
@endsection
