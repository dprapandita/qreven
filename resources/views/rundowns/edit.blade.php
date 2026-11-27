@extends('layouts.event-management')

@section('event-content')
<form method="POST" action="{{ route('rundowns.update', [$event, $rundown]) }}" class="card card-pad max-w-2xl">
    @csrf @method('PUT')
    <h2 class="font-display mb-6 text-lg font-bold text-ink">Edit sesi</h2>
    @include('rundowns._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-line pt-6">
        <a href="{{ route('rundowns.index', $event) }}" class="btn-secondary">Batal</a>
        <button class="btn-accent">Simpan perubahan</button>
    </div>
</form>
@endsection
