@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wider text-qr-text inline-block border-b-2 border-qr-accent pb-1">
                JENIS TIKET
            </h1>
            <p class="text-xs sm:text-sm text-qr-muted mt-2 tracking-wide">
                Kelola tipe tiket, kuota tersedia, dan penentuan harga untuk setiap event.
            </p>
        </div>
        <div>
            <a href="{{ Route::has('ticket-types.create') ? route('ticket-types.create') : '#' }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-qr-accent hover:bg-qr-accent-dark text-white font-semibold text-xs sm:text-sm uppercase tracking-wider transition duration-150 shadow-lg shadow-qr-accent/25 hover:shadow-qr-accent/40 hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Tambah Tiket</span>
            </a>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-qr-card border border-qr-border rounded-xl shadow-xl overflow-hidden transition duration-200 hover:border-qr-accent/30 hover:shadow-[0_0_25px_rgba(139,92,246,0.1)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-qr-surface border-b border-qr-border">
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted">
                            Nama Tiket
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted">
                            Event
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted">
                            Harga
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted text-center">
                            Kuota
                        </th>
                        <th scope="col" class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-qr-muted text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-qr-border">
                    @forelse ($ticketTypes as $ticketType)
                        <tr class="bg-qr-card hover:bg-qr-surface/60 transition duration-150 group">
                            <!-- Nama Tiket -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-qr-accent-glow border border-qr-accent/30 flex items-center justify-center shrink-0 text-qr-accent-light group-hover:border-qr-accent transition duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold text-sm text-qr-text block group-hover:text-qr-accent-light transition duration-150">
                                            {{ $ticketType->nama_tiket }}
                                        </span>
                                        <span class="text-[11px] text-qr-muted font-mono">
                                            ID: #{{ $ticketType->id }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Event Relation -->
                            <td class="py-4 px-5 text-sm text-qr-text">
                                <div class="flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-qr-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium text-qr-text truncate max-w-xs">
                                        {{ $ticketType->event->nama_event ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Harga (Rp Format) -->
                            <td class="py-4 px-5">
                                <span class="font-bold text-sm text-qr-success font-mono tracking-tight">
                                    Rp {{ number_format($ticketType->harga, 0, ',', '.') }}
                                </span>
                            </td>

                            <!-- Kuota -->
                            <td class="py-4 px-5 text-center">
                                @if ($ticketType->kuota > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-qr-surface border border-qr-border text-qr-text font-mono">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-accent-light"></span>
                                        {{ number_format($ticketType->kuota, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-qr-danger/10 text-qr-danger border border-qr-danger/30 font-mono">
                                        <span class="w-1.5 h-1.5 rounded-full bg-qr-danger"></span>
                                        Habis
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi (Edit & Delete) -->
                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a href="{{ Route::has('ticket-types.edit') ? route('ticket-types.edit', $ticketType->id) : '#' }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-qr-border bg-qr-surface hover:border-qr-accent hover:text-qr-accent-light text-qr-muted text-xs font-semibold transition duration-150" title="Edit Jenis Tiket">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <form action="{{ Route::has('ticket-types.destroy') ? route('ticket-types.destroy', $ticketType->id) : '#' }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis tiket ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-qr-danger/30 bg-qr-danger/10 hover:bg-qr-danger hover:border-qr-danger text-qr-danger hover:text-white text-xs font-semibold transition duration-150" title="Hapus Jenis Tiket">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 px-5 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-14 h-14 rounded-2xl bg-qr-surface border border-qr-border flex items-center justify-center text-qr-muted">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-bold uppercase tracking-wider text-qr-text">
                                            Belum Ada Jenis Tiket
                                        </p>
                                        <p class="text-xs text-qr-muted max-w-sm">
                                            Mulai tambahkan jenis tiket baru seperti VIP, Regular, atau Early Bird untuk event Anda.
                                        </p>
                                    </div>
                                    <a href="{{ Route::has('ticket-types.create') ? route('ticket-types.create') : '#' }}" class="mt-2 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-qr-accent hover:bg-qr-accent-dark text-white text-xs font-semibold uppercase tracking-wider transition duration-150 shadow-md shadow-qr-accent/20">
                                        + Tambah Tiket Pertama
                                    </a>
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