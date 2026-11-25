@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wider text-qr-text inline-block border-b-2 border-qr-accent pb-1">
                PESANAN TIKET
            </h1>
            <p class="text-xs sm:text-sm text-qr-muted mt-2 tracking-wide">
                Daftar semua transaksi pemesanan tiket acara dan status pembayarannya.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-qr-surface border border-qr-border text-xs font-mono text-qr-muted">
                <span class="w-2 h-2 rounded-full bg-qr-accent animate-pulse"></span>
                <span>Total: {{ count($orders) }} Pesanan</span>
            </span>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-qr-card border border-qr-border rounded-xl shadow-xl overflow-hidden transition duration-200 hover:border-qr-accent/30 hover:shadow-[0_0_25px_rgba(139,92,246,0.1)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-qr-surface border-b border-qr-border">
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted">
                            Order ID
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted">
                            Event
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted">
                            Total Harga
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted text-center">
                            Status
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted">
                            Metode Pembayaran
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted text-center">
                            Jumlah Tiket
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted text-right">
                            Tanggal Pesan
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-qr-border">
                    @forelse ($orders as $order)
                        <tr class="bg-qr-card hover:bg-qr-surface/60 transition duration-150 group">
                            <!-- Order ID -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-xs text-qr-accent-light group-hover:text-qr-accent transition duration-150">
                                        #{{ $order->id }}
                                    </span>
                                </div>
                            </td>

                            <!-- Event Name -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2 max-w-xs">
                                    <svg class="w-3.5 h-3.5 text-qr-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium text-sm text-qr-text truncate group-hover:text-qr-accent-light transition duration-150">
                                        {{ $order->event->nama_event ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Total Harga -->
                            <td class="py-4 px-5">
                                <span class="font-bold text-sm text-qr-success font-mono tracking-tight">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </span>
                            </td>

                            <!-- Status Pembayaran (Badge with Colored Dots) -->
                            <td class="py-4 px-5 text-center">
                                @php
                                    $status = strtolower($order->status_pembayaran ?? 'pending');
                                @endphp

                                @if (in_array($status, ['paid', 'lunas', 'berhasil', 'success']))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-success/10 text-qr-success border border-qr-success/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-success"></span>
                                        <span class="uppercase tracking-wider">Paid</span>
                                    </span>
                                @elseif (in_array($status, ['cancelled', 'canceled', 'batal', 'danger', 'failed', 'gagal']))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-danger/10 text-qr-danger border border-qr-danger/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-danger"></span>
                                        <span class="uppercase tracking-wider">Cancelled</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-warning/10 text-qr-warning border border-qr-warning/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-warning animate-pulse"></span>
                                        <span class="uppercase tracking-wider">Pending</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Metode Pembayaran -->
                            <td class="py-4 px-5">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-qr-surface border border-qr-border text-xs text-qr-muted font-medium uppercase tracking-wide">
                                    <svg class="w-3.5 h-3.5 text-qr-accent-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    <span>{{ $order->metode_pembayaran ?: 'QRIS / Transfer' }}</span>
                                </div>
                            </td>

                            <!-- Jumlah Tiket -->
                            <td class="py-4 px-5 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-qr-surface border border-qr-border text-qr-text font-mono">
                                    <span class="w-1.5 h-1.5 rounded-full bg-qr-accent-light"></span>
                                    <span>{{ $order->tickets ? $order->tickets->count() : 0 }} Tiket</span>
                                </span>
                            </td>

                            <!-- Tanggal Pesan (Created At) -->
                            <td class="py-4 px-5 text-right">
                                <span class="text-xs text-qr-muted font-mono whitespace-nowrap">
                                    {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 px-5 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-14 h-14 rounded-2xl bg-qr-surface border border-qr-border flex items-center justify-center text-qr-muted">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-bold uppercase tracking-wider text-qr-text">
                                            Belum Ada Pesanan Tiket
                                        </p>
                                        <p class="text-xs text-qr-muted max-w-sm">
                                            Transaksi pemesanan tiket yang dilakukan pengunjung akan otomatis tercatat di sini.
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection