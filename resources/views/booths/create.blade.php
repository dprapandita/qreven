@extends('layouts.event-management')

@section('event-content')
<form method="POST" action="{{ route('booths.store', $event) }}" class="card card-pad max-w-2xl">
    @csrf
    <h2 class="font-display mb-6 text-lg font-bold text-ink">Booth baru</h2>
    @include('booths._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-line pt-6">
        <a href="{{ route('booths.index', $event) }}" class="btn-secondary">Batal</a>
        <button class="btn-accent">Simpan booth</button>
    </div>
</form>
@endsection
