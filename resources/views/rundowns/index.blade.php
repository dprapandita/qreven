@extends('layouts.app')

@section('content')
<div class="py-6 sm:py-8 space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-qr-border">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-qr-surface border border-qr-border text-qr-accent-light uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-qr-accent animate-pulse"></span>
                Agenda & Rundown
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-wider text-qr-text">
                RUNDOWN ACARA
            </h1>
            <p class="text-sm text-qr-muted mt-1">
                Jadwal dan susunan kegiatan acara secara terstruktur dan kronologis
            </p>
        </div>

        <div>
            <a href="{{ Route::has('rundowns.create') ? route('rundowns.create') : '#' }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-qr-accent hover:bg-qr-accent-dark text-white font-semibold text-sm shadow-lg shadow-qr-accent/25 hover:shadow-qr-accent/40 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Kegiatan</span>
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if (session('success'))
        <div class="bg-qr-card border border-qr-success/40 rounded-xl p-4 flex items-center justify-between text-qr-success shadow-lg shadow-qr-success/5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-qr-success/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-qr-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-qr-muted hover:text-qr-text transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-qr-card border border-qr-danger/40 rounded-xl p-4 flex items-center justify-between text-qr-danger shadow-lg shadow-qr-danger/5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-qr-danger/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-qr-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-qr-muted hover:text-qr-text transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Visual Timeline Section --}}
    <div class="relative pl-6 sm:pl-8 space-y-6 sm:space-y-8 before:absolute before:left-3 before:top-4 before:bottom-4 before:w-0.5 before:bg-gradient-to-b before:from-qr-accent before:via-qr-border before:to-qr-border/20">
        @forelse ($rundowns as $rundown)
            <div class="relative group">
                {{-- Timeline Dot (Accent Color) --}}
                <div class="absolute -left-[27px] sm:-left-[29px] top-6 w-5 h-5 rounded-full bg-qr-card border-2 border-qr-accent shadow-[0_0_12px_rgba(139,92,246,0.6)] flex items-center justify-center group-hover:scale-125 group-hover:bg-qr-accent transition-all duration-300">
                    <span class="w-2 h-2 rounded-full bg-qr-accent-light group-hover:bg-white transition-colors"></span>
                </div>

                {{-- Timeline Card --}}
                <div class="bg-qr-card border border-qr-border hover:border-qr-accent/50 rounded-2xl p-5 sm:p-6 transition-all duration-300 hover:shadow-[0_0_25px_rgba(139,92,246,0.15)]">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Time Badge (waktu_mulai - waktu_selesai) --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-qr-surface border border-qr-border text-qr-accent-light">
                                <svg class="w-3.5 h-3.5 text-qr-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>
                                    {{ \Carbon\Carbon::parse($rundown->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($rundown->waktu_selesai)->format('H:i') }}
                                </span>
                            </span>

                            {{-- Event Relation Badge --}}
                            @if ($rundown->event)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-qr-accent-glow border border-qr-accent/30 text-qr-accent-light">
                                    <span class="w-1.5 h-1.5 rounded-full bg-qr-accent"></span>
                                    <span>{{ $rundown->event->nama_event }}</span>
                                </span>
                            @endif

                            {{-- Date Indicator --}}
                            <span class="text-xs text-qr-muted">
                                {{ \Carbon\Carbon::parse($rundown->waktu_mulai)->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 sm:opacity-90 group-hover:opacity-100 transition-opacity">
                            <a href="{{ Route::has('rundowns.edit') ? route('rundowns.edit', $rundown->id) : '#' }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-qr-border hover:border-qr-accent/60 bg-qr-surface hover:bg-qr-accent/10 text-xs font-medium text-qr-muted hover:text-qr-text transition-all"
                               title="Edit Kegiatan">
                                <svg class="w-3.5 h-3.5 text-qr-accent-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span>Edit</span>
                            </a>

                            <form action="{{ Route::has('rundowns.destroy') ? route('rundowns.destroy', $rundown->id) : '#' }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');"
                                  class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-qr-border hover:border-qr-danger/60 bg-qr-surface hover:bg-qr-danger/10 text-xs font-medium text-qr-muted hover:text-qr-danger transition-all cursor-pointer"
                                        title="Hapus Kegiatan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Nama Kegiatan (Bold) --}}
                    <h3 class="text-lg sm:text-xl font-bold text-qr-text tracking-wide group-hover:text-qr-accent-light transition-colors mb-2">
                        {{ $rundown->nama_kegiatan }}
                    </h3>

                    {{-- Deskripsi (Muted) --}}
                    @if ($rundown->deskripsi)
                        <p class="text-sm text-qr-muted leading-relaxed whitespace-pre-line">
                            {{ $rundown->deskripsi }}
                        </p>
                    @else
                        <p class="text-xs text-qr-muted/50 italic">
                            Tidak ada deskripsi detail untuk kegiatan ini.
                        </p>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="relative bg-qr-card border border-dashed border-qr-border rounded-2xl p-10 sm:p-14 text-center">
                <div class="w-16 h-16 rounded-2xl bg-qr-surface border border-qr-border mx-auto flex items-center justify-center text-qr-accent mb-4 shadow-[0_0_20px_rgba(139,92,246,0.15)]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold uppercase tracking-wider text-qr-text mb-2">
                    Belum Ada Rundown Kegiatan
                </h3>
                <p class="text-sm text-qr-muted max-w-md mx-auto mb-6">
                    Belum ada susunan rundown yang ditambahkan untuk kegiatan ini. Mulai atur agenda acara Anda sekarang.
                </p>
                <a href="{{ Route::has('rundowns.create') ? route('rundowns.create') : '#' }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-qr-accent hover:bg-qr-accent-dark text-white font-semibold text-sm shadow-lg shadow-qr-accent/25 hover:shadow-qr-accent/40 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Tambah Kegiatan Pertama</span>
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection