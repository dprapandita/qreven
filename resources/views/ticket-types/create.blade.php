@extends('layouts.event-management')

@section('event-content')
<form method="POST" action="{{ route('ticket-types.store', $event) }}" class="card card-pad max-w-2xl">
    @csrf
    <h2 class="font-display mb-6 text-lg font-bold text-ink">Tiket baru</h2>
    @include('ticket-types._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-line pt-6">
        <a href="{{ route('ticket-types.index', $event) }}" class="btn-secondary">Batal</a>
        <button class="btn-accent">Simpan tiket</button>
    </div>
</form>
@endsection
