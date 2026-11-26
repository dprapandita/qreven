<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Qreven') }} · Platform manajemen event</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

{{-- Hero --}}
<section class="gradient-brand relative overflow-hidden text-white">
    <div class="pointer-events-none absolute -right-32 -top-32 size-[32rem] rounded-full bg-accent-500/30 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-40 -left-24 size-[28rem] rounded-full bg-brand-400/30 blur-3xl"></div>

    <header class="relative mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <span class="grid size-10 place-items-center rounded-xl bg-white/15 ring-1 ring-white/25"><x-icon name="qr" class="size-5" /></span>
            <span class="text-xl font-extrabold tracking-tight">{{ config('app.name', 'Qreven') }}</span>
        </a>
        <nav class="flex items-center gap-2">
            @auth
                @if(Route::has('dashboard'))<a href="{{ route('dashboard') }}" class="btn bg-white text-brand-700 hover:bg-brand-50">Buka dashboard</a>@endif
            @else
                @if(Route::has('login'))<a href="{{ route('login') }}" class="btn text-white hover:bg-white/10">Masuk</a>@endif
                @if(Route::has('register'))<a href="{{ route('register') }}" class="btn bg-white text-brand-700 hover:bg-brand-50">Daftar gratis</a>@endif
            @endauth
        </nav>
    </header>

    <div class="relative mx-auto max-w-4xl px-6 pb-28 pt-16 text-center sm:pt-24">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold ring-1 ring-white/25">
            <span class="size-1.5 rounded-full bg-accent-400"></span> Untuk penyelenggara event
        </span>
        <h1 class="mt-6 text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-6xl">
            Event rapi,<br><span class="bg-gradient-to-r from-brand-200 to-accent-400 bg-clip-text text-transparent">tanpa ribet.</span>
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg text-brand-100">
            Atur event, jual tiket, kelola booth vendor, susun rundown, dan check-in peserta dalam satu aplikasi.
        </p>
        <div class="mt-10 flex flex-wrap justify-center gap-3">
            @if(Route::has('register'))<a href="{{ route('register') }}" class="btn bg-white px-6 py-3 text-brand-700 shadow-xl shadow-brand-950/30 hover:bg-brand-50">Mulai sekarang</a>@endif
            @if(Route::has('login'))<a href="{{ route('login') }}" class="btn px-6 py-3 text-white ring-1 ring-white/30 hover:bg-white/10">Saya sudah punya akun</a>@endif
        </div>
    </div>
</section>

{{-- Fitur --}}
<section class="mx-auto -mt-14 max-w-6xl px-6 pb-20">
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([
            ['calendar', 'Manajemen event', 'Buat dan atur event beserta detail, lokasi, dan jadwalnya.'],
            ['ticket',   'Tiket fleksibel', 'Beberapa jenis tiket dengan harga dan kuota masing-masing.'],
            ['booth',    'Booth vendor',    'Kelola booth dan pantau pemesanan dari vendor.'],
            ['qr',       'Check-in cepat',  'Validasi tiket peserta di pintu masuk dalam hitungan detik.'],
        ] as [$icon, $title, $text])
            <div class="card card-pad shadow-soft transition hover:-translate-y-1">
                <span class="grid size-12 place-items-center rounded-2xl bg-brand-100 text-brand-700"><x-icon :name="$icon" class="size-6" /></span>
                <h3 class="mt-4 text-base font-extrabold text-brand-950">{{ $title }}</h3>
                <p class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="mx-auto max-w-6xl px-6 pb-20">
    <div class="gradient-brand relative overflow-hidden rounded-3xl px-8 py-14 text-center text-white">
        <div class="pointer-events-none absolute -right-10 -top-10 size-64 rounded-full bg-accent-500/30 blur-3xl"></div>
        <h2 class="relative text-3xl font-extrabold">Siap bikin event pertamamu?</h2>
        <p class="relative mx-auto mt-3 max-w-lg text-brand-200">Daftar dalam semenit dan langsung atur tiket serta rundown-nya.</p>
        @if(Route::has('register'))<a href="{{ route('register') }}" class="btn relative mt-8 bg-white px-6 py-3 text-brand-700 hover:bg-brand-50">Buat akun</a>@endif
    </div>
</section>

<footer class="border-t border-brand-100 py-8 text-center text-xs text-slate-500">© {{ date('Y') }} {{ config('app.name', 'Qreven') }}</footer>
</body>
</html>
