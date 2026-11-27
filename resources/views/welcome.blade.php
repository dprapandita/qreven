<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Qreven · Platform Manajemen & Penemuan Event</title>
    <meta name="description" content="Temukan pengalaman baru atau kelola event Anda, mulai dari penjualan tiket hingga operasional hari-H.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,600,700|jetbrains-mono:400,500,600&display=swap" rel="stylesheet">
    <x-theme-init />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas text-ink">
@php($events = $events ?? collect())

{{-- Top Navigation --}}
<header class="sticky top-0 z-30 border-b border-line bg-canvas/95 backdrop-blur-xs">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5">
            <span class="grid size-8 place-items-center rounded border border-ink bg-accent text-ink">
                <x-icon name="qr" class="size-4" />
            </span>
            <span class="font-display text-lg font-bold tracking-tight text-ink">Qreven</span>
        </a>

        <nav class="hidden items-center gap-7 text-sm font-medium text-muted md:flex" aria-label="Navigasi utama">
            @if($events->isNotEmpty())
                <a href="#event" class="transition-colors duration-150 hover:text-ink">Jelajahi Event</a>
            @endif
            <a href="#fitur" class="transition-colors duration-150 hover:text-ink">Fitur</a>
            <a href="#cara-kerja" class="transition-colors duration-150 hover:text-ink">Cara Kerja</a>
        </nav>

        <div class="flex items-center gap-2">
            <x-theme-toggle />
            @auth
                @if(Route::has('dashboard'))
                    <a href="{{ route('dashboard') }}" class="btn-primary btn-sm">Dashboard</a>
                @endif
            @else
                @if(Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-ghost btn-sm">Masuk</a>
                @endif
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-accent btn-sm">Daftar</a>
                @endif
            @endauth
        </div>
    </div>
</header>

<main>
    {{-- Hero: Asymmetrical Editorial --}}
    <section class="border-b border-line bg-surface">
        <div class="mx-auto grid max-w-6xl gap-12 px-6 py-16 lg:grid-cols-12 lg:items-center lg:py-24">
            <div class="lg:col-span-7">
                <span class="inline-flex items-center gap-2 rounded border border-ink bg-accent px-2.5 py-1 font-mono text-[11px] font-semibold uppercase tracking-wider text-ink">
                    Platform Event Indonesia
                </span>
                <h1 class="font-display mt-6 text-4xl font-bold leading-[1.06] tracking-tight text-ink sm:text-6xl">
                    Temukan event. Kelola semuanya.
                </h1>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-muted sm:text-lg">
                    Temukan pengalaman baru atau kelola event Anda, mulai dari penjualan tiket hingga operasional hari-H.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    @auth
                        @if(Route::has('dashboard'))
                            <a href="{{ route('dashboard') }}" class="btn-accent px-5 py-2.5">Buka Dashboard</a>
                        @endif
                        @if(Route::has('events.create'))
                            <a href="{{ route('events.create') }}" class="btn-secondary px-5 py-2.5">Buat Event Baru</a>
                        @endif
                    @else
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-accent px-5 py-2.5">Mulai Kelola Event</a>
                        @endif
                        @if($events->isNotEmpty())
                            <a href="#event" class="btn-secondary px-5 py-2.5">Jelajahi Event</a>
                        @elseif(Route::has('login'))
                            <a href="{{ route('login') }}" class="btn-secondary px-5 py-2.5">Masuk ke Akun</a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Editorial Interface Preview (Product-oriented, no fake metrics) --}}
            <div class="lg:col-span-5">
                <div class="card-brutal panel-dark overflow-hidden">
                    <div class="flex items-center justify-between border-b border-white/15 px-5 py-3.5">
                        <span class="font-mono text-xs uppercase tracking-wider text-[#D9F478]">Alur Operasional</span>
                        <span class="font-mono text-[11px] text-white/60">subdomain.qreven</span>
                    </div>
                    <div class="divide-y divide-white/10 p-5">
                        @foreach([
                            ['01', 'Halaman Publik & Subdomain', 'Publikasikan jadwal, lokasi, dan daftar tiket di alamat khusus event.'],
                            ['02', 'Penjualan Tiket & Kuota', 'Atur kategori tiket gratis maupun berbayar dengan kontrol kuota otomatis.'],
                            ['03', 'Booth Vendor & Rundown', 'Kelola slot booth pameran, persetujuan vendor, dan linimasa sesi.'],
                            ['04', 'Check-in QR Hari-H', 'Validasi kode tiket peserta di pintu masuk secara instan.'],
                        ] as [$step, $title, $desc])
                            <div class="flex gap-4 py-3.5 first:pt-0 last:pb-0">
                                <span class="font-mono text-xs font-semibold text-[#D9F478]">{{ $step }}</span>
                                <div>
                                    <p class="font-display text-sm font-semibold text-white">{{ $title }}</p>
                                    <p class="mt-0.5 text-xs leading-relaxed text-white/70">{{ $desc }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Dynamic Event Discovery (only rendered when eligible public events exist) --}}
    @if($events->isNotEmpty())
        <section id="event" class="border-b border-line py-16 lg:py-20">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="eyebrow">Event Publik</p>
                        <h2 class="font-display mt-1.5 text-2xl font-bold tracking-tight text-ink sm:text-3xl">Jelajahi event terdekat</h2>
                    </div>
                    @auth
                        @if(Route::has('events.index'))
                            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-ink underline underline-offset-4 hover:text-muted">Kelola event Anda →</a>
                        @endif
                    @endauth
                </div>

                @php($gridCols = $events->count() === 1 ? 'grid-cols-1' : ($events->count() === 2 ? 'sm:grid-cols-2' : 'sm:grid-cols-2 lg:grid-cols-3'))
                <div class="mt-8 grid gap-6 {{ $gridCols }}">
                    @foreach($events as $event)
                        <article class="card flex flex-col overflow-hidden transition-colors duration-150 hover:border-ink">
                            <a href="{{ route('event.home', ['event_subdomain' => $event->subdomain]) }}" class="panel-dark block border-b px-5 py-6">
                                @if(!empty($event->poster))
                                    <img src="{{ $event->poster }}" alt="{{ $event->nama_event }}" class="mb-4 h-36 w-full rounded object-cover">
                                @endif
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-mono text-[11px] uppercase tracking-wider text-[#D9F478]">
                                        <x-date :value="$event->tanggal_mulai ?? null" format="d M Y" />
                                    </span>
                                    <span class="font-mono text-[11px] text-white/65">{{ $event->subdomain }}</span>
                                </div>
                                <h3 class="font-display mt-3 line-clamp-2 text-xl font-bold leading-snug text-white">{{ $event->nama_event }}</h3>
                            </a>

                            <div class="flex flex-1 flex-col justify-between p-5">
                                <div class="space-y-2 text-sm text-muted">
                                    @if(!empty($event->lokasi))
                                        <p class="flex items-center gap-2">
                                            <x-icon name="pin" class="size-4 shrink-0 text-ink" />
                                            <span class="truncate">{{ $event->lokasi }}</span>
                                        </p>
                                    @endif
                                    @if($event->ticket_types_min_harga !== null)
                                        <p class="flex items-center gap-2 font-medium text-ink">
                                            <x-icon name="ticket" class="size-4 shrink-0 text-ink" />
                                            <span>Mulai <x-money :value="$event->ticket_types_min_harga" /></span>
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-5 flex items-center gap-2 border-t border-line pt-4">
                                    <a href="{{ route('event.home', ['event_subdomain' => $event->subdomain]) }}" class="btn-secondary btn-sm flex-1">Detail Event</a>
                                    <a href="{{ route('event.tickets', ['event_subdomain' => $event->subdomain]) }}" class="btn-primary btn-sm">Tiket</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Fitur: Editorial List --}}
    <section id="fitur" class="border-b border-line py-16 lg:py-20">
        <div class="mx-auto grid max-w-6xl gap-12 px-6 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <p class="eyebrow">Kapabilitas Sistem</p>
                <h2 class="font-display mt-2 text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                    Semua modul operasional dalam satu alur kerja.
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-muted">
                    Dirancang agar penyelenggara tidak perlu berpindah antar-spreadsheet untuk mengatur tiket, vendor, dan pintu masuk.
                </p>
            </div>

            <div class="divide-y divide-line border-t border-b border-line lg:col-span-8">
                @foreach([
                    ['Tiket & Kuota Fleksibel', 'Buat berbagai kategori tiket beserta harga dan batas kuota yang berkurang otomatis saat pesanan dibuat.'],
                    ['Halaman Subdomain Khusus', 'Setiap event mendapatkan halaman publik dan halaman daftar tiket tersendiri pada subdomain event.'],
                    ['Manajemen Booth Vendor', 'Atur slot booth, terima pengajuan booking dari vendor, dan setujui atau tolak dari satu panel.'],
                    ['Rundown Acara Terstruktur', 'Susun urutan sesi beserta waktu mulai dan selesai yang langsung tampil di halaman publik event.'],
                    ['Check-in QR & Tim Kolaborasi', 'Undang anggota tim dengan peran Administrator, Finance, atau Check-in untuk memvalidasi tiket peserta.'],
                ] as [$title, $desc])
                    <div class="grid gap-2 py-5 sm:grid-cols-12 sm:items-baseline">
                        <h3 class="font-display text-base font-bold text-ink sm:col-span-5">{{ $title }}</h3>
                        <p class="text-sm leading-relaxed text-muted sm:col-span-7">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Cara Kerja --}}
    <section id="cara-kerja" class="border-b border-line bg-surface py-16 lg:py-20">
        <div class="mx-auto max-w-6xl px-6">
            <p class="eyebrow">Cara Kerja</p>
            <h2 class="font-display mt-2 text-2xl font-bold tracking-tight text-ink sm:text-3xl">Dari draft hingga hari-H</h2>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach([
                    ['01', 'Buat Event & Subdomain', 'Tentukan nama event, jadwal, lokasi, dan alamat subdomain unik untuk halaman publik Anda.'],
                    ['02', 'Lengkapi Tiket, Booth & Rundown', 'Tambahkan kategori tiket, slot booth untuk vendor, dan susunan jadwal acara sebelum diterbitkan.'],
                    ['03', 'Pantau Pesanan & Check-in', 'Kelola pembayaran tiket, konfirmasi booth, dan lakukan validasi kode tiket QR di lokasi acara.'],
                ] as [$num, $title, $desc])
                    <div class="card card-pad bg-canvas/50">
                        <span class="inline-block rounded border border-ink bg-accent px-2 py-0.5 font-mono text-xs font-bold text-ink">{{ $num }}</span>
                        <h3 class="font-display mt-4 text-lg font-bold text-ink">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="py-16 lg:py-20">
        <div class="mx-auto max-w-6xl px-6">
            <div class="card-brutal panel-dark flex flex-col items-start justify-between gap-6 p-8 sm:flex-row sm:items-center sm:p-10">
                <div class="max-w-xl">
                    <h2 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Siap menjalankan event berikutnya?</h2>
                    <p class="mt-2 text-sm text-white/75">Buat akun penyelenggara dan siapkan halaman event Anda dalam hitungan menit.</p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-3">
                    @auth
                        @if(Route::has('dashboard'))
                            <a href="{{ route('dashboard') }}" class="btn-accent px-5 py-2.5">Buka Dashboard</a>
                        @endif
                    @else
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-accent px-5 py-2.5">Buat Akun</a>
                        @endif
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}" class="btn border border-white/30 text-white hover:bg-white/10">Masuk</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="border-t border-line bg-surface py-8">
    <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 text-xs text-muted sm:flex-row">
        <p class="font-mono">© {{ date('Y') }} Qreven</p>
        <div class="flex items-center gap-6">
            <a href="#fitur" class="hover:text-ink">Fitur</a>
            <a href="#cara-kerja" class="hover:text-ink">Cara Kerja</a>
            @auth
                @if(Route::has('dashboard'))<a href="{{ route('dashboard') }}" class="hover:text-ink">Dashboard</a>@endif
            @else
                @if(Route::has('login'))<a href="{{ route('login') }}" class="hover:text-ink">Masuk</a>@endif
                @if(Route::has('register'))<a href="{{ route('register') }}" class="hover:text-ink">Daftar</a>@endif
            @endauth
        </div>
    </div>
</footer>
</body>
</html>
