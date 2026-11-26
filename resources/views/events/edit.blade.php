@extends('layouts.app')
@section('title', 'Edit ' . $event->name)

@section('content')
<x-page-header title="Edit event" :subtitle="$event->name" :back="route('events.show', $event)" />

<form method="POST" action="{{ route('events.update', $event) }}" class="card card-pad max-w-3xl">
    @csrf @method('PUT')
    @include('events._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-brand-100 pt-6">
        <a href="{{ route('events.show', $event) }}" class="btn-secondary">Batal</a>
        <button type="submit" class="btn-primary">Simpan perubahan</button>
    </div>
</form>

<div class="card card-pad mt-8 max-w-3xl border-rose-200">
    <h2 class="font-extrabold text-rose-700">Zona berbahaya</h2>
    <p class="mt-1 text-sm text-slate-500">Menghapus event akan ikut menghapus tiket, booth, dan rundown miliknya.</p>
    <div class="mt-4">
        <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini secara permanen?')">
            @csrf @method('DELETE')
            <button class="btn-danger"><x-icon name="trash" class="size-4" /> Hapus event</button>
        </form>
    </div>
</div>
@endsection
