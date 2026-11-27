@extends('layouts.event-management')

@section('event-content')
<form method="POST" action="{{ route('rundowns.store', $event) }}" class="card card-pad max-w-2xl">
    @csrf
    <h2 class="mb-6 text-lg font-extrabold text-brand-950">Sesi baru</h2>
    @include('rundowns._form')
    <div class="mt-8 flex justify-end gap-3 border-t border-brand-100 pt-6">
        <a href="{{ route('rundowns.index', $event) }}" class="btn-secondary">Batal</a>
        <button class="btn-primary">Simpan sesi</button>
    </div>
</form>
@endsection
