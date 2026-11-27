@extends('layouts.event-management')

@section('event-content')
<form method="POST" action="{{ route('booths.update', [$event, $booth]) }}" class="card card-pad max-w-2xl">
    @csrf @method('PUT')
    <h2 class="font-display mb-6 text-lg font-bold text-ink">Edit booth</h2>
    @include('booths._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-line pt-6">
        <a href="{{ route('booths.index', $event) }}" class="btn-secondary">Batal</a>
        <button class="btn-accent">Simpan perubahan</button>
    </div>
</form>
@endsection
