@extends('layouts.app')
@section('title', 'Check-in')

@section('content')
<x-page-header title="Check-in Peserta" subtitle="Pilih event dan scan atau masukkan kode tiket untuk validasi masuk." />

<div class="grid gap-6 lg:grid-cols-5">
    <section class="card card-pad lg:col-span-3">
        <div class="flex items-center gap-3 border-b border-line pb-4">
            <span class="grid size-10 place-items-center rounded border border-ink bg-accent text-ink">
                <x-icon name="qr" class="size-5" />
            </span>
            <div>
                <h2 class="font-display text-base font-bold text-ink">Validasi Tiket</h2>
                <p class="text-xs text-muted">Masukkan kode tiket peserta (UUID).</p>
            </div>
        </div>

        @if($events->isEmpty())
            <x-empty icon="calendar" title="Belum ada event untuk check-in" text="Buat atau bergabung ke event terlebih dahulu untuk melakukan check-in." />
        @else
            <form method="POST" action="{{ route('checkin.verify') }}" class="mt-6 space-y-5">
                @csrf
                <x-field name="event_id" label="Event" required>
                    <select id="event_id" name="event_id" class="input" required>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}" @selected(old('event_id') === $ev->id)>{{ $ev->nama_event }}</option>
                        @endforeach
                    </select>
                </x-field>

                <x-field name="kode_tiket" label="Kode tiket" required>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input id="kode_tiket" name="kode_tiket" value="{{ old('kode_tiket') }}" autofocus autocomplete="off" required
                               class="input flex-1 py-3 font-mono text-sm font-semibold" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
                        <button type="submit" class="btn-accent px-6 py-3"><x-icon name="check" class="size-4" /> Check-in</button>
                    </div>
                </x-field>
            </form>
        @endif
    </section>

    <section class="card card-pad lg:col-span-2">
        <h2 class="font-display text-base font-bold text-ink">Hasil Terakhir</h2>
        @if(session('ticket'))
            @php($checkedTicket = session('ticket'))
            <div class="mt-4 rounded-md border border-success/40 bg-success/10 p-4">
                <span class="badge badge-green">Berhasil</span>
                <p class="mt-2 font-mono text-xs font-semibold text-ink">{{ $checkedTicket->kode_tiket }}</p>
                <p class="mt-1 text-xs text-muted">Status: {{ $checkedTicket->status instanceof \BackedEnum ? $checkedTicket->status->value : $checkedTicket->status }}</p>
            </div>
        @else
            <p class="mt-4 text-sm text-muted">Belum ada validasi check-in pada sesi ini.</p>
        @endif
    </section>
</div>
@endsection
