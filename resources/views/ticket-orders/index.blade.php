@extends('layouts.event-management')

@section('event-content')
@php
    $current = request('status', 'all');
    $filters = ['all' => 'Semua', 'pending' => 'Menunggu', 'paid' => 'Lunas', 'cancelled' => 'Dibatalkan'];
@endphp

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Pesanan tiket</h2>
        <p class="text-sm text-muted">Pantau pembelian dan status pembayaran.</p>
    </div>
    <div class="inline-flex rounded-md border border-line bg-surface p-1">
        @foreach($filters as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $key === 'all' ? null : $key, 'page' => null]) }}"
               class="rounded px-3 py-1.5 text-xs font-medium transition-colors duration-150 {{ $current === $key ? 'bg-ink text-surface' : 'text-muted hover:text-ink' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="card overflow-hidden">
    @if($ticketOrders->isEmpty())
        <x-empty icon="orders" title="Belum ada pesanan" text="Pesanan akan muncul di sini setelah peserta membeli tiket." />
    @else
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                @foreach($ticketOrders as $o)
                    <tr>
                        <td class="font-mono text-xs font-semibold text-ink">#{{ \Illuminate\Support\Str::limit($o->id, 8, '') }}</td>
                        <td>
                            <p class="font-semibold text-ink">{{ $o->user->name ?? '—' }}</p>
                            <p class="text-xs text-muted">{{ $o->user->email ?? '' }}</p>
                        </td>
                        <td class="font-mono text-sm font-semibold text-ink"><x-money :value="$o->total_harga ?? 0" /></td>
                        <td><x-badge :status="$o->status_pembayaran ?? null" /></td>
                        <td class="whitespace-nowrap text-xs text-muted"><x-date :value="$o->created_at ?? null" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pager :items="$ticketOrders" />
    @endif
</div>
@endsection
