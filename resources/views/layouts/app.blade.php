<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Qreven'))</title>

    @fonts

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-qr-bg text-qr-text antialiased min-h-screen flex flex-col font-sans" x-data="{ sidebarOpen: false }">

    <!-- Top Navbar -->
    <header class="sticky top-0 z-40 w-full bg-qr-surface border-b border-qr-border">
        <div class="h-16 px-4 sm:px-6 flex items-center justify-between">
            <!-- Left: Mobile Menu Toggle & Logo -->
            <div class="flex items-center gap-3">
                <!-- Mobile Hamburger Button -->
                <button 
                    type="button" 
                    @click="sidebarOpen = !sidebarOpen" 
                    class="md:hidden p-2 rounded-lg text-qr-muted hover:text-qr-text hover:bg-qr-card transition-colors focus:outline-none"
                    aria-label="Toggle navigation menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="sidebarOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-1.5 font-bold text-xl tracking-wider text-qr-text select-none">
                    <span class="text-qr-accent font-black">✦</span>
                    <span><span class="text-qr-accent">QR</span>EVEN</span>
                </a>
            </div>

            <!-- Center: Nav Links -->
            <nav class="hidden md:flex items-center gap-6 lg:gap-8">
                <a 
                    href="{{ Route::has('events') ? route('events') : '#' }}" 
                    class="text-sm font-medium uppercase tracking-wider transition-colors duration-200 {{ request()->is('events*') ? 'text-qr-accent' : 'text-qr-muted hover:text-qr-text' }}"
                >
                    Events
                </a>
                <a 
                    href="{{ Route::has('booths') ? route('booths') : '#' }}" 
                    class="text-sm font-medium uppercase tracking-wider transition-colors duration-200 {{ request()->is('booths*') ? 'text-qr-accent' : 'text-qr-muted hover:text-qr-text' }}"
                >
                    Booths
                </a>
                <a 
                    href="{{ Route::has('tickets') ? route('tickets') : (Route::has('tiket') ? route('tiket') : '#') }}" 
                    class="text-sm font-medium uppercase tracking-wider transition-colors duration-200 {{ request()->is('tiket*') || request()->is('tickets*') ? 'text-qr-accent' : 'text-qr-muted hover:text-qr-text' }}"
                >
                    Tiket
                </a>
                <a 
                    href="{{ Route::has('rundown') ? route('rundown') : '#' }}" 
                    class="text-sm font-medium uppercase tracking-wider transition-colors duration-200 {{ request()->is('rundown*') ? 'text-qr-accent' : 'text-qr-muted hover:text-qr-text' }}"
                >
                    Rundown
                </a>
                <a 
                    href="{{ Route::has('checkin') ? route('checkin') : (Route::has('check-in') ? route('check-in') : '#') }}" 
                    class="text-sm font-medium uppercase tracking-wider transition-colors duration-200 {{ request()->is('checkin*') || request()->is('check-in*') ? 'text-qr-accent' : 'text-qr-muted hover:text-qr-text' }}"
                >
                    Check-in
                </a>
            </nav>

            <!-- Right: Login Button -->
            <div class="flex items-center gap-3">
                @auth
                    <span class="text-sm text-qr-muted hidden sm:inline">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}" class="inline">
                        @csrf
                        <button type="submit" class="border border-qr-border hover:border-qr-danger text-qr-muted hover:text-qr-danger text-sm font-medium px-3.5 py-1.5 rounded-lg transition-colors">
                            Logout
                        </button>
                    </form>
                @else
                    <a 
                        href="{{ Route::has('login') ? route('login') : '#' }}" 
                        class="border border-qr-accent text-qr-accent hover:bg-qr-accent hover:text-qr-text text-sm font-medium px-4 py-1.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-qr-accent/25"
                    >
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- App Body (Sidebar + Content) -->
    <div class="flex flex-1 relative">
        <!-- Mobile Sidebar Backdrop -->
        <div 
            x-show="sidebarOpen" 
            x-cloak
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-xs z-30 md:hidden"
        ></div>

        <!-- Left Sidebar (64px wide on desktop) -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed md:sticky top-16 left-0 z-30 h-[calc(100vh-4rem)] w-64 md:w-16 bg-qr-surface border-r border-qr-border flex flex-col justify-between py-4 transition-transform duration-300 ease-in-out shrink-0"
        >
            <div class="flex flex-col gap-2 px-2">
                @php
                    $navItems = [
                        [
                            'title' => 'Events',
                            'emoji' => '🎪',
                            'route' => Route::has('events') ? route('events') : '#',
                            'active' => request()->is('events*'),
                        ],
                        [
                            'title' => 'Booths',
                            'emoji' => '🏪',
                            'route' => Route::has('booths') ? route('booths') : '#',
                            'active' => request()->is('booths*'),
                        ],
                        [
                            'title' => 'Tiket',
                            'emoji' => '🎫',
                            'route' => Route::has('tickets') ? route('tickets') : (Route::has('tiket') ? route('tiket') : '#'),
                            'active' => request()->is('tiket*') || request()->is('tickets*'),
                        ],
                        [
                            'title' => 'Rundown',
                            'emoji' => '📋',
                            'route' => Route::has('rundown') ? route('rundown') : '#',
                            'active' => request()->is('rundown*'),
                        ],
                        [
                            'title' => 'Check-in',
                            'emoji' => '✅',
                            'route' => Route::has('checkin') ? route('checkin') : (Route::has('check-in') ? route('check-in') : '#'),
                            'active' => request()->is('checkin*') || request()->is('check-in*'),
                        ],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <a 
                        href="{{ $item['route'] }}" 
                        title="{{ $item['title'] }}"
                        class="group relative flex items-center md:justify-center px-3 md:px-0 py-3 rounded-xl transition-all duration-200 {{ $item['active'] ? 'bg-qr-accent-glow text-qr-accent border border-qr-accent/30 shadow-[0_0_15px_rgba(139,92,246,0.2)]' : 'text-qr-muted hover:text-qr-text hover:bg-qr-card/60 border border-transparent' }}"
                    >
                        <span class="text-xl leading-none select-none">{{ $item['emoji'] }}</span>
                        <span class="ml-3 text-sm font-medium md:hidden">{{ $item['title'] }}</span>

                        <!-- Tooltip on Desktop -->
                        <span class="hidden md:block absolute left-full ml-3 px-2.5 py-1 bg-qr-card text-qr-text text-xs font-medium rounded-md border border-qr-border shadow-xl whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-150 z-50">
                            {{ $item['title'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </aside>

        <!-- Main Content Area & Footer -->
        <div class="flex-1 flex flex-col min-w-0">
            <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-center justify-between p-4 rounded-xl bg-qr-card border border-qr-success/30 text-qr-text shadow-lg">
                        <div class="flex items-center gap-3">
                            <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-qr-success opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-qr-success"></span>
                            </span>
                            <span class="text-sm font-medium text-qr-success">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-qr-muted hover:text-qr-text text-sm p-1 rounded-lg hover:bg-qr-surface transition-colors focus:outline-none" aria-label="Close alert">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-center justify-between p-4 rounded-xl bg-qr-card border border-qr-danger/30 text-qr-text shadow-lg">
                        <div class="flex items-center gap-3">
                            <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-qr-danger opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-qr-danger"></span>
                            </span>
                            <span class="text-sm font-medium text-qr-danger">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-qr-muted hover:text-qr-text text-sm p-1 rounded-lg hover:bg-qr-surface transition-colors focus:outline-none" aria-label="Close alert">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="border-t border-qr-border py-6 px-4 text-center text-sm text-qr-muted">
                © 2026 Qreven. All rights reserved.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>