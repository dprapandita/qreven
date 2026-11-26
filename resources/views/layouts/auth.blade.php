<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') · {{ config('app.name', 'Qreven') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-white">
<div class="grid min-h-screen lg:grid-cols-2">

    {{-- Panel kiri: brand --}}
    <div class="gradient-brand relative hidden overflow-hidden p-12 text-white lg:flex lg:flex-col lg:justify-between">
        <div class="pointer-events-none absolute -right-24 -top-24 size-96 rounded-full bg-accent-500/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-20 size-96 rounded-full bg-brand-400/30 blur-3xl"></div>

        <a href="{{ url('/') }}" class="relative flex items-center gap-3">
            <span class="grid size-10 place-items-center rounded-xl bg-white/15 ring-1 ring-white/25"><x-icon name="qr" class="size-5" /></span>
            <span class="text-xl font-extrabold tracking-tight">{{ config('app.name', 'Qreven') }}</span>
        </a>

        <div class="relative max-w-md">
            <h2 class="text-4xl font-extrabold leading-tight">Kelola event, tiket, dan booth dalam satu tempat.</h2>
            <p class="mt-4 text-brand-200">Dari rundown sampai check-in peserta, semuanya rapi dan terpantau lewat satu dashboard.</p>
            <ul class="mt-8 space-y-3 text-sm text-brand-100">
                @foreach(['Jenis tiket dan kuota fleksibel', 'Pemesanan booth untuk vendor', 'Check-in cepat dengan kode QR'] as $f)
                    <li class="flex items-center gap-3"><span class="grid size-6 place-items-center rounded-full bg-white/15"><x-icon name="check" class="size-3.5" /></span>{{ $f }}</li>
                @endforeach
            </ul>
        </div>

        <p class="relative text-xs text-brand-300">© {{ date('Y') }} {{ config('app.name', 'Qreven') }}</p>
    </div>

    {{-- Panel kanan: form --}}
    <div class="flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <a href="{{ url('/') }}" class="mb-8 flex items-center gap-2 lg:hidden">
                <span class="grid size-9 place-items-center rounded-xl bg-brand-600 text-white"><x-icon name="qr" class="size-5" /></span>
                <span class="text-lg font-extrabold text-brand-950">{{ config('app.name', 'Qreven') }}</span>
            </a>
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
