@extends('layouts.event-management')

@section('event-content')
<form method="POST" action="{{ route('events.booths.update', [$event, $booth]) }}" class="card card-pad max-w-2xl">
    @csrf @method('PUT')
    <h2 class="mb-6 text-lg font-extrabold text-brand-950">Edit booth</h2>
    @include('booths._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-brand-100 pt-6">
        <a href="{{ route('events.booths.index', $event) }}" class="btn-secondary">Batal</a>
        <button class="btn-primary">Simpan perubahan</button>
    </div>
</form>
@endsection
