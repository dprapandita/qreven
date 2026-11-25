@extends('layouts.app')

@section('content')
<div x-data="{
    ticketCode: '{{ old('kode_tiket', '') }}',
    hasServerSuccess: {{ session('success') ? 'true' : 'false' }},
    hasServerError: {{ session('error') ? 'true' : 'false' }},
    clientStatus: null,
    clientMessage: '',
    clientTicket: null
}" class="py-8 sm:py-12 max-w-xl mx-auto space-y-8">

    {{-- Scan-line CSS Keyframe Animation --}}
    <style>
        @keyframes scanline {
            0% {
                top: 0%;
                opacity: 0;
            }
            15% {
                opacity: 1;
            }
            85% {
                opacity: 1;
            }
            100% {
                top: 100%;
                opacity: 0;
            }
        }
        .animate-scanline {
            animation: scanline 2.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
    </style>

    {{-- Page Header --}}
    <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-qr-surface border border-qr-border text-qr-accent-light uppercase tracking-widest">
            <span class="w-1.5 h-1.5 rounded-full bg-qr-accent animate-pulse"></span>
            Pintu Masuk & Gate Scanner
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-wider text-qr-text">
            CHECK-IN
        </h1>
        <p class="text-sm sm:text-base text-qr-muted">
            Scan atau masukkan kode tiket
        </p>
    </div>

    {{-- Large Centered Input Card with Moving Violet Scan-line Animation --}}
    <div class="relative overflow-hidden bg-qr-card border border-qr-border hover:border-qr-accent/50 rounded-2xl p-6 sm:p-10 shadow-2xl shadow-black/50 transition-all duration-300">
        {{-- Moving Violet Scan-Line --}}
        <div class="pointer-events-none absolute inset-x-0 h-1 bg-gradient-to-r from-transparent via-qr-accent-light to-transparent shadow-[0_0_15px_#8B5CF6,0_0_30px_#8B5CF6] animate-scanline z-10"></div>

        {{-- Subtle corner glowing ambient --}}
        <div class="pointer-events-none absolute -top-16 -right-16 w-36 h-36 bg-qr-accent/10 rounded-full blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 -left-16 w-36 h-36 bg-qr-accent/10 rounded-full blur-2xl"></div>

        <form action="#" method="POST" class="relative z-20 space-y-6">
            @csrf

            <div>
                <label for="kode_tiket" class="block text-center text-xs font-bold uppercase tracking-widest text-qr-muted mb-3">
                    KODE TIKET PESERTA
                </label>
                <div class="relative">
                    <input type="text"
                           name="kode_tiket"
                           id="kode_tiket"
                           x-model="ticketCode"
                           value="{{ old('kode_tiket') }}"
                           required
                           autofocus
                           placeholder="Masukkan kode tiket..."
                           class="w-full text-center text-xl sm:text-2xl font-mono tracking-widest uppercase bg-qr-surface border-2 border-qr-border focus:border-qr-accent focus:ring-4 focus:ring-qr-accent/20 rounded-xl px-4 py-4 text-qr-text placeholder:text-qr-muted/40 outline-none transition-all duration-200">
                </div>
                <p class="text-center text-xs text-qr-muted/70 mt-2">
                    Gunakan scanner barcode/QR atau ketik kode tiket secara manual
                </p>
            </div>

            <div>
                <button type="submit"
                        class="w-full bg-qr-accent hover:bg-qr-accent-dark text-white font-bold py-4 px-6 rounded-xl text-sm sm:text-base tracking-widest uppercase transition-all duration-200 shadow-lg shadow-qr-accent/30 hover:shadow-qr-accent/50 active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>VERIFIKASI</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Result Area Below (Initially Hidden, shown via Blade or Alpine.js) --}}

    {{-- 1. Server-side Success (Blade Flash) --}}
    @if (session('success'))
        <div class="bg-qr-card border border-qr-success/50 bg-gradient-to-b from-qr-success/10 to-transparent rounded-2xl p-6 sm:p-8 shadow-xl shadow-qr-success/10 relative overflow-hidden animate-fade-in">
            <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-qr-success/10 blur-2xl pointer-events-none"></div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-qr-success/20 border border-qr-success/40 flex items-center justify-center shrink-0 text-qr-success shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-qr-success/20 text-qr-success mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-qr-success"></span>
                        Terverifikasi
                    </div>
                    <h3 class="text-xl font-extrabold uppercase tracking-wider text-qr-text">
                        Check-in Berhasil!
                    </h3>
                    <p class="text-sm text-qr-muted mt-0.5">
                        {{ is_string(session('success')) ? session('success') : 'Tiket valid dan telah diverifikasi untuk akses masuk.' }}
                    </p>

                    {{-- Ticket Info --}}
                    <div class="mt-5 p-4 rounded-xl bg-qr-surface border border-qr-border grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="text-qr-muted uppercase tracking-wider block font-medium">Kode Tiket</span>
                            <span class="font-mono text-qr-text font-bold text-sm tracking-wider">
                                {{ session('ticket')->kode_tiket ?? (is_array(session('ticket')) ? session('ticket')['kode_tiket'] : session('kode_tiket', old('kode_tiket', '-'))) }}
                            </span>
                        </div>

                        <div>
                            <span class="text-qr-muted uppercase tracking-wider block font-medium">Waktu Check-In</span>
                            <span class="text-qr-text font-semibold">
                                {{ now()->format('d M Y, H:i:s') }} WIB
                            </span>
                        </div>

                        @if (session('ticket') && isset(session('ticket')->ticketType))
                            <div>
                                <span class="text-qr-muted uppercase tracking-wider block font-medium">Kategori Tiket</span>
                                <span class="text-qr-accent-light font-semibold">
                                    {{ session('ticket')->ticketType->nama_tipe }}
                                </span>
                            </div>
                        @endif

                        <div>
                            <span class="text-qr-muted uppercase tracking-wider block font-medium">Status</span>
                            <span class="inline-flex items-center gap-1 text-qr-success font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-qr-success"></span>
                                Valid & Digunakan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 2. Server-side Error (Blade Flash) --}}
    @if (session('error'))
        <div class="bg-qr-card border border-qr-danger/50 bg-gradient-to-b from-qr-danger/10 to-transparent rounded-2xl p-6 sm:p-8 shadow-xl shadow-qr-danger/10 relative overflow-hidden animate-fade-in">
            <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-qr-danger/10 blur-2xl pointer-events-none"></div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-qr-danger/20 border border-qr-danger/40 flex items-center justify-center shrink-0 text-qr-danger shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-qr-danger/20 text-qr-danger mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-qr-danger"></span>
                        Gagal Verifikasi
                    </div>
                    <h3 class="text-xl font-extrabold uppercase tracking-wider text-qr-text">
                        Check-in Gagal!
                    </h3>
                    <p class="text-sm text-qr-muted mt-1 leading-relaxed">
                        {{ session('error') }}
                    </p>
                    <div class="mt-4 p-3 rounded-lg bg-qr-surface/70 border border-qr-border text-xs text-qr-muted">
                        Pastikan kode tiket terdaftar dengan benar dan belum pernah digunakan.
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 3. Alpine.js Client-side Result Area (when triggered via JS/Alpine) --}}
    <div x-cloak x-show="clientStatus === 'success'" class="bg-qr-card border border-qr-success/50 bg-gradient-to-b from-qr-success/10 to-transparent rounded-2xl p-6 sm:p-8 shadow-xl shadow-qr-success/10 relative overflow-hidden">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-qr-success/20 border border-qr-success/40 flex items-center justify-center shrink-0 text-qr-success">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-qr-success/20 text-qr-success mb-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-qr-success"></span>
                    Verified
                </div>
                <h3 class="text-xl font-extrabold uppercase tracking-wider text-qr-text">
                    Check-in Berhasil!
                </h3>
                <p class="text-sm text-qr-muted mt-0.5" x-text="clientMessage || 'Tiket valid dan berhasil diverifikasi.'"></p>
                <div class="mt-4 p-4 rounded-xl bg-qr-surface border border-qr-border grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-qr-muted uppercase tracking-wider block font-medium">Kode Tiket</span>
                        <span class="font-mono text-qr-text font-bold text-sm tracking-wider" x-text="clientTicket?.kode_tiket || ticketCode || '-'"></span>
                    </div>
                    <div>
                        <span class="text-qr-muted uppercase tracking-wider block font-medium">Status</span>
                        <span class="text-qr-success font-semibold">Valid & Digunakan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-cloak x-show="clientStatus === 'error'" class="bg-qr-card border border-qr-danger/50 bg-gradient-to-b from-qr-danger/10 to-transparent rounded-2xl p-6 sm:p-8 shadow-xl shadow-qr-danger/10 relative overflow-hidden">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-qr-danger/20 border border-qr-danger/40 flex items-center justify-center shrink-0 text-qr-danger">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-qr-danger/20 text-qr-danger mb-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-qr-danger"></span>
                    Error
                </div>
                <h3 class="text-xl font-extrabold uppercase tracking-wider text-qr-text">
                    Check-in Gagal!
                </h3>
                <p class="text-sm text-qr-muted mt-1 leading-relaxed" x-text="clientMessage || 'Tiket tidak ditemukan atau sudah digunakan.'"></p>
            </div>
        </div>
    </div>
</div>
@endsection