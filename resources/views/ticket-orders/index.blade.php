@extends('layouts.event-management')

@section('event-content')
@php
    $current = request('status', 'all');
    $filters = ['all' => 'Semua', 'pending' => 'Menunggu', 'paid' => 'Lunas', 'cancelled' => 'Dibatalkan'];
@endphp

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-lg font-extrabold text-brand-950">Pesanan tiket</h2>
        <p class="text-sm text-slate-500">Pantau pembelian dan status pembayaran.</p>
    </div>
    <div class="inline-flex rounded-xl border border-brand-100 bg-white p-1">
        @foreach($filters as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $key === 'all' ? null : $key, 'page' => null]) }}"
               class="rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $current === $key ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-brand-50' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="card overflow-hidden">
    @if($ticketOrders->isEmpty())
        <x-empty icon="orders" title="Belum ada pesanan" text="Pesanan akan muncul di sini setelah peserta membeli tiket." />
    @else
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Kode</th><th>Pembeli</th><th>Jumlah</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                @foreach($ticketOrders as $o)
                    <tr>
                        <td class="font-mono text-xs font-bold text-brand-700">{{ $o->code ?? '#' . $o->id }}</td>
                        <td>
                            <p class="font-semibold text-brand-950">{{ $o->buyer_name ?? $o->name ?? '—' }}</p>
                            <p class="text-xs text-slate-500">{{ $o->buyer_email ?? $o->email ?? '' }}</p>
                        </td>
                        <td>{{ $o->quantity ?? '—' }}</td>
                        <td class="font-semibold"><x-money :value="$o->total ?? $o->total_price ?? 0" /></td>
                        <td><x-badge :status="$o->status ?? null" /></td>
                        <td class="whitespace-nowrap text-slate-500"><x-date :value="$o->created_at ?? null" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pager :items="$ticketOrders" />
    @endif
</div>
@endsection
