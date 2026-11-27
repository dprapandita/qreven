<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') · {{ config('app.name', 'Qreven') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,600,700|jetbrains-mono:400,500,600&display=swap" rel="stylesheet">
    <x-theme-init />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-canvas text-ink">
<div class="grid min-h-screen lg:grid-cols-12">

    {{-- Panel kiri: editorial dark --}}
    <div class="panel-dark hidden border-r p-12 lg:col-span-5 lg:flex lg:flex-col lg:justify-between">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 self-start">
            <span class="grid size-9 place-items-center rounded border border-[#D9F478] bg-[#D9F478] text-[#191B18]">
                <x-icon name="qr" class="size-4" />
            </span>
            <span class="font-display text-xl font-bold tracking-tight">{{ config('app.name', 'Qreven') }}</span>
        </a>

        <div class="max-w-md">
            <span class="inline-block rounded border border-white/20 px-2.5 py-1 font-mono text-[11px] uppercase tracking-widest text-[#D9F478]">
                Platform Event
            </span>
            <h2 class="font-display mt-5 text-4xl font-bold leading-[1.1] tracking-tight">
                Kelola event, tiket, dan booth dalam satu tempat.
            </h2>
            <p class="mt-4 text-sm leading-relaxed text-white/70">
                Dari penyusunan rundown hingga validasi check-in QR di pintu masuk, semua operasional event terstruktur rapi.
            </p>
            <ul class="mt-8 space-y-3 border-t border-white/15 pt-6 text-sm text-white/85">
                @foreach(['Kategori tiket dan kuota terukur', 'Manajemen booth dan pengajuan vendor', 'Check-in cepat dengan kode tiket QR'] as $f)
                    <li class="flex items-center gap-3">
                        <span class="grid size-5 shrink-0 place-items-center rounded bg-[#D9F478] text-[#191B18]"><x-icon name="check" class="size-3.5" /></span>
                        {{ $f }}
                    </li>
                @endforeach
            </ul>
        </div>

        <p class="font-mono text-xs text-white/50">© {{ date('Y') }} {{ config('app.name', 'Qreven') }}</p>
    </div>

    {{-- Panel kanan: form --}}
    <div class="relative flex items-center justify-center bg-surface px-6 py-12 lg:col-span-7">
        <div class="absolute right-6 top-6">
            <x-theme-toggle />
        </div>
        <div class="w-full max-w-md">
            <a href="{{ url('/') }}" class="mb-8 inline-flex items-center gap-2.5 lg:hidden">
                <span class="grid size-8 place-items-center rounded border border-ink bg-accent text-[#191B18]">
                    <x-icon name="qr" class="size-4" />
                </span>
                <span class="font-display text-lg font-bold text-ink">{{ config('app.name', 'Qreven') }}</span>
            </a>
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
