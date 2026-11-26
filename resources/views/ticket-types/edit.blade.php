@extends('layouts.event-management')

@section('event-content')
<form method="POST" action="{{ route('events.ticket-types.update', [$event, $ticketType]) }}" class="card card-pad max-w-2xl">
    @csrf @method('PUT')
    <h2 class="mb-6 text-lg font-extrabold text-brand-950">Edit tiket</h2>
    @include('ticket-types._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-brand-100 pt-6">
        <a href="{{ route('events.ticket-types.index', $event) }}" class="btn-secondary">Batal</a>
        <button class="btn-primary">Simpan perubahan</button>
    </div>
</form>
@endsection
