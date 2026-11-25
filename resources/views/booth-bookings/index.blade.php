@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-qr-border">
        <div>
            <h1 class="text-3xl font-extrabold tracking-wider uppercase text-qr-text flex items-center gap-3">
                <span class="text-qr-accent">✦</span> BOOKING BOOTH
            </h1>
            <p class="text-sm text-qr-muted mt-1">Kelola dan tinjau permohonan pemesanan booth</p>
        </div>
        <div>
            <a href="{{ Route::has('booths.index') ? route('booths.index') : '#' }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-qr-border hover:border-qr-accent/50 text-qr-muted hover:text-qr-text bg-qr-surface hover:bg-qr-card text-sm font-semibold transition duration-150">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Lihat Daftar Booth</span>
            </a>
        </div>
    </div>

    <!-- Booking Table Card -->
    <div class="bg-qr-card border border-qr-border rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-qr-surface/90 border-b border-qr-border text-[11px] font-bold uppercase tracking-widest text-qr-muted">
                        <th scope="col" class="py-4 px-6">Booth Name</th>
                        <th scope="col" class="py-4 px-6">User</th>
                        <th scope="col" class="py-4 px-6">Status</th>
                        <th scope="col" class="py-4 px-6">Catatan</th>
                        <th scope="col" class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-qr-border/60 text-sm">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-qr-surface/50 transition duration-150">
                            <!-- Booth Name Column -->
                            <td class="py-4 px-6 font-bold text-qr-text">
                                <div class="flex items-center gap-2">
                                    <span class="text-qr-accent">✦</span>
                                    <span>{{ $booking->booth->nama_booth ?? 'Booth #' . $booking->booth_id }}</span>
                                </div>
                                @if(isset($booking->booth->event))
                                    <span class="text-xs text-qr-muted block mt-0.5 ml-5">
                                        {{ $booking->booth->event->nama_event ?? $booking->booth->event->name }}
                                    </span>
                                @endif
                            </td>

                            <!-- User Column -->
                            <td class="py-4 px-6 text-qr-text">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-qr-surface border border-qr-border flex items-center justify-center text-xs font-bold text-qr-accent-light">
                                        {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-qr-text">{{ $booking->user->name ?? 'User #' . $booking->user_id }}</div>
                                        @if(isset($booking->user->email))
                                            <div class="text-xs text-qr-muted">{{ $booking->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Status Column (badge: menunggu=warning, disetujui=success, ditolak=danger) -->
                            <td class="py-4 px-6">
                                @if($booking->status === 'menunggu')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-warning/15 text-qr-warning border border-qr-warning/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-warning animate-pulse"></span>
                                        Menunggu
                                    </span>
                                @elseif($booking->status === 'disetujui')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-success/15 text-qr-success border border-qr-success/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-success"></span>
                                        Disetujui
                                    </span>
                                @elseif($booking->status === 'ditolak')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-danger/15 text-qr-danger border border-qr-danger/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-danger"></span>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-surface text-qr-muted border border-qr-border">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-muted"></span>
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Catatan Column -->
                            <td class="py-4 px-6 text-qr-muted max-w-xs">
                                <span class="line-clamp-2" title="{{ $booking->catatan ?? '-' }}">
                                    {{ $booking->catatan ?: '-' }}
                                </span>
                            </td>

                            <!-- Aksi Column -->
                            <td class="py-4 px-6 text-right">
                                @if($booking->status === 'menunggu')
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Setujui (success) -->
                                        <form action="{{ Route::has('booth-bookings.update') ? route('booth-bookings.update', $booking->id) : '#' }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-qr-success hover:bg-emerald-600 text-white font-semibold text-xs shadow-md shadow-qr-success/20 transition duration-150 active:scale-[0.98]">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Setujui</span>
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak (danger) -->
                                        <form action="{{ Route::has('booth-bookings.update') ? route('booth-bookings.update', $booking->id) : '#' }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-qr-danger hover:bg-red-600 text-white font-semibold text-xs shadow-md shadow-qr-danger/20 transition duration-150 active:scale-[0.98]">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                <span>Tolak</span>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-qr-muted/70 italic">
                                        Selesai diproses
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-qr-surface border border-qr-border text-qr-muted flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold uppercase tracking-wider text-qr-text">Belum Ada Booking</h4>
                                    <p class="text-xs text-qr-muted mt-1">Saat ini belum ada data permohonan booking booth.</p>
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