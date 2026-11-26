@extends('layouts.event-management')

@section('event-content')
<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="text-lg font-extrabold text-brand-950">Jenis tiket</h2>
        <p class="text-sm text-slate-500">Atur harga dan kuota tiap kategori.</p>
    </div>
    <a href="{{ route('events.ticket-types.create', $event) }}" class="btn-primary"><x-icon name="plus" class="size-4" /> Tambah tiket</a>
</div>

<div class="card overflow-hidden">
    @if($ticketTypes->isEmpty())
        <x-empty icon="ticket" title="Belum ada jenis tiket" text="Tambahkan minimal satu jenis tiket agar peserta bisa memesan.">
            <a href="{{ route('events.ticket-types.create', $event) }}" class="btn-primary">Tambah tiket</a>
        </x-empty>
    @else
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Nama</th><th>Harga</th><th>Kuota</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                @foreach($ticketTypes as $t)
                    <tr>
                        <td>
                            <p class="font-bold text-brand-950">{{ $t->name }}</p>
                            @if(!empty($t->description))<p class="line-clamp-1 text-xs text-slate-500">{{ $t->description }}</p>@endif
                        </td>
                        <td class="font-semibold text-brand-700"><x-money :value="$t->price ?? 0" /></td>
                        <td>{{ $t->quota ?? '∞' }}</td>
                        <td class="text-right">
                            <a href="{{ route('events.ticket-types.edit', [$event, $t]) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="size-4" /> Edit</a>
                            <x-delete-button :action="route('events.ticket-types.destroy', [$event, $t])"><x-icon name="trash" class="size-4" /></x-delete-button>
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
