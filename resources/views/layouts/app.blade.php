<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name', 'Qreven') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full">
@php
    $nav = [
        ['dashboard',    'Dashboard',  'grid',     'dashboard'],
        ['events.index', 'Event Saya', 'calendar', 'events.*'],
    ];
@endphp

<input type="checkbox" id="nav-toggle" class="peer hidden">
<label for="nav-toggle" class="fixed inset-0 z-30 hidden bg-brand-950/50 backdrop-blur-sm peer-checked:block lg:hidden!"></label>

{{-- Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-brand-950 text-brand-100 transition-transform peer-checked:translate-x-0 lg:translate-x-0">
    <div class="flex h-16 items-center gap-3 px-6">
        <span class="grid size-9 place-items-center rounded-xl bg-gradient-to-br from-brand-400 to-accent-500 text-white shadow-lg shadow-brand-900/50">
            <x-icon name="qr" class="size-5" />
        </span>
        <span class="text-lg font-extrabold tracking-tight text-white">{{ config('app.name', 'Qreven') }}</span>
    </div>

    <nav class="mt-4 flex-1 space-y-1 px-3">
        @foreach($nav as [$route, $label, $icon, $pattern])
            @continue(!Route::has($route))
            <a href="{{ route($route) }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition
                      {{ request()->routeIs($pattern) ? 'bg-brand-600 text-white shadow-md shadow-brand-950/40' : 'text-brand-200 hover:bg-white/10 hover:text-white' }}">
                <x-icon :name="$icon" /> {{ $label }}
            </a>
        @endforeach
    </nav>

    @auth
    <div class="m-3 rounded-2xl bg-white/5 p-3">
        <div class="flex items-center gap-3">
            <span class="grid size-9 shrink-0 place-items-center rounded-full bg-brand-500 text-sm font-bold text-white">
                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-brand-300">{{ auth()->user()->email }}</p>
            </div>
        </div>
        @if(Route::has('logout'))
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button class="flex w-full items-center justify-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:bg-white/20">
                <x-icon name="logout" class="size-4" /> Keluar
            </button>
        </form>
        @endif
    </div>
    @endauth
</aside>

{{-- Main --}}
<div class="lg:pl-64">
    <header class="sticky top-0 z-20 flex h-14 items-center gap-3 border-b border-brand-100 bg-white/80 px-4 backdrop-blur lg:hidden">
        <label for="nav-toggle" class="grid size-9 cursor-pointer place-items-center rounded-lg text-brand-700 hover:bg-brand-100"><x-icon name="menu" /></label>
        <span class="font-extrabold text-brand-950">{{ config('app.name', 'Qreven') }}</span>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success') || session('status'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                <x-icon name="check" class="mt-0.5 size-4 shrink-0" /> {{ session('success') ?? session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-semibold">Ada data yang perlu diperbaiki:</p>
                <ul class="mt-1 list-inside list-disc text-rose-700">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>
@stack('scripts')
</body>
</html>
