<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name', 'Qreven') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,600,700|jetbrains-mono:400,500,600&display=swap" rel="stylesheet">
    <x-theme-init />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full bg-canvas text-ink">
@php
    $navGroups = [
        'Utama' => [
            ['dashboard',    'Dashboard',  'grid',     ['dashboard']],
            ['events.index', 'Event Saya', 'calendar', ['events.*', 'ticket-types.*', 'orders.*', 'booths.*', 'booth-bookings.*', 'rundowns.*', 'members.*']],
        ],
        'Operasional' => [
            ['checkin',      'Check-in',   'qr',       ['checkin*']],
        ],
    ];
@endphp

<input type="checkbox" id="nav-toggle" class="peer hidden">
<label for="nav-toggle" class="fixed inset-0 z-30 hidden bg-ink/40 peer-checked:block lg:hidden!"></label>

{{-- Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-line bg-surface transition-transform duration-150 peer-checked:translate-x-0 lg:translate-x-0">
    <div class="flex h-16 items-center justify-between border-b border-line px-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <span class="grid size-8 place-items-center rounded border border-ink bg-accent text-ink">
                <x-icon name="qr" class="size-4" />
            </span>
            <span class="font-display text-lg font-bold tracking-tight text-ink">{{ config('app.name', 'Qreven') }}</span>
        </a>
        <x-theme-toggle />
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5" aria-label="Navigasi utama">
        @foreach($navGroups as $groupLabel => $items)
            <div>
                <p class="eyebrow px-3 pb-2">{{ $groupLabel }}</p>
                <div class="space-y-1">
                    @foreach($items as [$route, $label, $icon, $patterns])
                        @continue(!Route::has($route))
                        @php($active = request()->routeIs($patterns))
                        <a href="{{ route($route) }}"
                           class="flex items-center justify-between rounded-md px-3 py-2 text-sm font-medium transition-colors duration-150
                                  {{ $active ? 'bg-ink text-surface' : 'text-muted hover:bg-canvas hover:text-ink' }}">
                            <span class="flex items-center gap-2.5">
                                <x-icon :name="$icon" class="size-4" />
                                {{ $label }}
                            </span>
                            @if($active)
                                <span class="size-1.5 rounded-xs bg-accent" aria-hidden="true"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    @auth
    <div class="border-t border-line p-3">
        <div class="rounded-md border border-line bg-canvas p-3">
            <div class="flex items-center gap-2.5">
                <span class="font-display grid size-8 shrink-0 place-items-center rounded border border-ink bg-surface text-xs font-bold text-ink">
                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-ink">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-muted">{{ auth()->user()->email }}</p>
                </div>
            </div>
            @if(Route::has('logout'))
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="btn-secondary btn-sm w-full">
                    <x-icon name="logout" class="size-3.5" /> Keluar
                </button>
            </form>
            @endif
        </div>
    </div>
    @endauth
</aside>

{{-- Main --}}
<div class="lg:pl-64">
    <header class="sticky top-0 z-20 flex h-14 items-center justify-between border-b border-line bg-surface px-4 lg:hidden">
        <div class="flex items-center gap-3">
            <label for="nav-toggle" class="grid size-9 cursor-pointer place-items-center rounded-md border border-line text-ink hover:bg-canvas" aria-label="Buka menu">
                <x-icon name="menu" class="size-4" />
            </label>
            <span class="font-display font-bold text-ink">{{ config('app.name', 'Qreven') }}</span>
        </div>
        <x-theme-toggle />
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success') || session('status'))
            <div class="mb-6 flex items-start gap-2.5 rounded-md border border-success/40 bg-success/10 px-4 py-3 text-sm font-medium text-success">
                <x-icon name="check" class="mt-0.5 size-4 shrink-0" />
                <span>{{ session('success') ?? session('status') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-md border border-error/40 bg-error/10 px-4 py-3 text-sm font-medium text-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-md border border-error/40 bg-error/10 px-4 py-3 text-sm text-error">
                <p class="font-semibold">Ada data yang perlu diperbaiki:</p>
                <ul class="mt-1 list-inside list-disc">
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
