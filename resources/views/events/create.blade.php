@extends('layouts.app')
@section('title', 'Event Baru')

@section('content')
<x-page-header title="Event baru" subtitle="Isi detail dasar, tiket dan booth bisa ditambah setelahnya." :back="route('events.index')" />

<form method="POST" action="{{ route('events.store') }}" class="card card-pad max-w-3xl">
    @csrf
    @include('events._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-line pt-6">
        <a href="{{ route('events.index') }}" class="btn-secondary">Batal</a>
        <button type="submit" class="btn-accent">Simpan event</button>
    </div>
</form>
@endsection
