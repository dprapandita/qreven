@extends('layouts.event-management')

@section('event-content')
<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Jenis tiket</h2>
        <p class="text-sm text-muted">Atur harga dan kuota tiap kategori.</p>
    </div>
    <a href="{{ route('ticket-types.create', $event) }}" class="btn-accent"><x-icon name="plus" class="size-4" /> Tambah tiket</a>
</div>

<div class="card overflow-hidden">
    @if($ticketTypes->isEmpty())
        <x-empty icon="ticket" title="Belum ada jenis tiket" text="Tambahkan minimal satu jenis tiket agar peserta bisa memesan.">
            <a href="{{ route('ticket-types.create', $event) }}" class="btn-accent">Tambah tiket</a>
        </x-empty>
    @else
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Nama</th><th>Harga</th><th>Kuota</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                @foreach($ticketTypes as $t)
                    <tr>
                        <td>
                            <p class="font-display font-bold text-ink">{{ $t->nama_tiket }}</p>
                        </td>
                        <td class="font-mono text-sm font-semibold text-ink"><x-money :value="$t->harga ?? 0" /></td>
                        <td class="font-mono text-sm text-muted">{{ $t->kuota ?? '∞' }}@if($t->capacity) / {{ $t->capacity }}@endif</td>
                        <td class="text-right">
                            <a href="{{ route('ticket-types.edit', [$event, $t]) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                            <x-delete-button :action="route('ticket-types.destroy', [$event, $t])"><x-icon name="trash" class="size-4" /></x-delete-button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pager :items="$ticketTypes" />
    @endif
</div>
@endsection
