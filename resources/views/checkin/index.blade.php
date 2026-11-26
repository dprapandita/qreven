@extends('layouts.event-management')

@section('event-content')
<div class="grid gap-6 lg:grid-cols-5">
    <section class="card card-pad lg:col-span-3">
        <div class="flex items-center gap-3">
            <span class="grid size-12 place-items-center rounded-2xl bg-brand-600 text-white shadow-lg shadow-brand-600/30"><x-icon name="qr" class="size-6" /></span>
            <div>
                <h2 class="text-lg font-extrabold text-brand-950">Check-in peserta</h2>
                <p class="text-sm text-slate-500">Scan QR dengan scanner, atau ketik kode tiket.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('events.checkin.store', $event) }}" class="mt-6">
            @csrf
            <label for="code" class="label">Kode tiket</label>
            <div class="mt-1.5 flex flex-col gap-3 sm:flex-row">
                <input id="code" name="code" value="{{ old('code') }}" autofocus autocomplete="off" required
                       class="input flex-1 py-4 text-center font-mono text-lg font-bold uppercase tracking-widest" placeholder="XXXX-XXXX">
                <button class="btn-primary px-8 py-4 text-base"><x-icon name="check" class="size-5" /> Check-in</button>
            </div>
            @error('code')<p class="error-text mt-2">{{ $message }}</p>@enderror
        </form>
    </section>

    <section class="card overflow-hidden lg:col-span-2">
        <div class="border-b border-brand-100 px-5 py-4">
            <h2 class="font-extrabold text-brand-950">Check-in terakhir</h2>
        </div>
        @php $recent = $checkins ?? $recent ?? collect(); @endphp
        @forelse($recent as $c)
            <div class="flex items-center gap-3 border-b border-brand-100/70 px-5 py-3 last:border-0">
                <span class="grid size-9 place-items-center rounded-full bg-emerald-100 text-emerald-700"><x-icon name="check" class="size-4" /></span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-brand-950">{{ $c->holder_name ?? $c->name ?? $c->code ?? 'Tiket' }}</p>
                    <p class="text-xs text-slate-500"><x-date :value="$c->checked_in_at ?? $c->updated_at ?? null" format="H:i" /></p>
                </div>
            </div>
        @empty
            <x-empty icon="qr" title="Belum ada check-in" text="Peserta yang sudah masuk akan tampil di sini." />
        @endforelse
    </section>
</div>
@endsection
