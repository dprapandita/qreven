<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="dark">

        <title>{{ config('app.name', 'Qreven') }} — Electric Violet Event Platform</title>

        @fonts

        <!-- Google Fonts Preconnect / Fallback -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,700;1,800&display=swap" rel="stylesheet">

        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Standalone Fallback & Animation Styles -->
        <style>
            :root {
                --color-qr-bg: #0B0B14;
                --color-qr-surface: #14142B;
                --color-qr-card: #1E1E3A;
                --color-qr-border: #252545;
                --color-qr-accent: #8B5CF6;
                --color-qr-accent-light: #A78BFA;
                --color-qr-accent-dark: #7C3AED;
                --color-qr-accent-glow: #8B5CF620;
                --color-qr-text: #FFFFFF;
                --color-qr-muted: #9CA3AF;
                --color-qr-success: #10B981;
                --color-qr-warning: #F59E0B;
                --color-qr-danger: #EF4444;
                --color-qr-info: #3B82F6;
            }

            html {
                scroll-behavior: smooth;
                background-color: var(--color-qr-bg);
            }

            body {
                background-color: var(--color-qr-bg);
                color: var(--color-qr-text);
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }

            /* Subtle entrance animations */
            @keyframes fadeInUp {
                0% {
                    opacity: 0;
                    transform: translateY(24px);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pulseGlow {
                0%, 100% {
                    opacity: 0.18;
                    transform: translate(-50%, -50%) scale(1);
                }
                50% {
                    opacity: 0.30;
                    transform: translate(-50%, -50%) scale(1.08);
                }
            }

            .animate-fade-in-up {
                animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            .anim-delay-100 { animation-delay: 100ms; }
            .anim-delay-200 { animation-delay: 200ms; }
            .anim-delay-300 { animation-delay: 300ms; }
            .anim-delay-400 { animation-delay: 400ms; }

            .hero-glow {
                animation: pulseGlow 8s ease-in-out infinite;
            }

            /* Custom dark scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
            }
            ::-webkit-scrollbar-track {
                background: var(--color-qr-bg);
            }
            ::-webkit-scrollbar-thumb {
                background: var(--color-qr-border);
                border-radius: 3px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: var(--color-qr-accent);
            }
        </style>
    </head>
    <body class="bg-qr-bg text-qr-text antialiased selection:bg-qr-accent selection:text-white min-h-screen flex flex-col overflow-x-hidden">

        <!-- 1. Full-width Navbar -->
        <header class="sticky top-0 z-50 w-full border-b border-qr-border/80 bg-qr-bg/85 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Logo: "✦ QREVEN" (accent on QR) -->
                <a href="/" class="flex items-center gap-2 group tracking-wider">
                    <span class="text-qr-accent text-xl transition-transform duration-300 group-hover:scale-125 group-hover:rotate-12">✦</span>
                    <span class="text-2xl font-black tracking-widest uppercase">
                        <span class="text-qr-accent">QR</span><span class="text-qr-text">EVEN</span>
                    </span>
                </a>

                <!-- Nav: EVENTS, BOOTHS, TIKET, RUNDOWN -->
                <nav class="hidden md:flex items-center gap-8 text-xs font-bold tracking-widest uppercase text-qr-muted">
                    <a href="{{ route('events.index') }}" class="hover:text-qr-accent transition-colors duration-200">EVENTS</a>
{{--                    <a href="{{ route('booths.index') }}" class="hover:text-qr-accent transition-colors duration-200">BOOTHS</a>--}}
{{--                    <a href="{{ route('ticket-types.index') }}" class="hover:text-qr-accent transition-colors duration-200">TIKET</a>--}}
{{--                    <a href="{{ route('rundowns.index') }}" class="hover:text-qr-accent transition-colors duration-200">RUNDOWN</a>--}}
                </nav>

                <!-- Right: LOGIN button with accent border -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="px-5 py-2 rounded-full border border-qr-accent/50 text-qr-text bg-qr-surface hover:border-qr-accent hover:bg-qr-accent/15 transition-all duration-200 text-xs font-bold tracking-wider uppercase"
                            >
                                DASHBOARD
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="px-6 py-2.5 rounded-full border border-qr-accent text-qr-accent hover:bg-qr-accent hover:text-white hover:shadow-[0_0_20px_rgba(139,92,246,0.4)] transition-all duration-300 text-xs font-bold tracking-wider uppercase"
                            >
                                LOGIN
                            </a>
                        @endauth
                    @else
                        <a
                            href="#"
                            class="px-6 py-2.5 rounded-full border border-qr-accent text-qr-accent hover:bg-qr-accent hover:text-white hover:shadow-[0_0_20px_rgba(139,92,246,0.4)] transition-all duration-300 text-xs font-bold tracking-wider uppercase"
                        >
                            LOGIN
                        </a>
                    @endif

                    <!-- Mobile Menu Hamburger -->
                    <button
                        type="button"
                        id="mobile-menu-toggle"
                        aria-label="Toggle navigation menu"
                        class="md:hidden p-2 rounded-lg text-qr-muted hover:text-qr-text hover:bg-qr-surface transition-colors focus:outline-none"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div id="mobile-menu" class="hidden md:hidden border-b border-qr-border bg-qr-surface/95 backdrop-blur-lg px-6 py-5">
                <nav class="flex flex-col gap-4 text-xs font-bold tracking-widest uppercase text-qr-muted">
                    <a href="{{ route('events.index') }}" class="hover:text-qr-accent transition-colors py-2 border-b border-qr-border/40">EVENTS</a>
{{--                    <a href="{{ route('booths') }}" class="hover:text-qr-accent transition-colors py-2 border-b border-qr-border/40">BOOTHS</a>--}}
{{--                    <a href="{{ route('tickets') }}" class="hover:text-qr-accent transition-colors py-2 border-b border-qr-border/40">TIKET</a>--}}
{{--                    <a href="{{ route('rundown') }}" class="hover:text-qr-accent transition-colors py-2 border-b border-qr-border/40">RUNDOWN</a>--}}
{{--                    <a href="{{ route('checkin') }}" class="hover:text-qr-accent transition-colors py-2">CHECK-IN</a>--}}
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">

            <!-- 2. Hero Section (full viewport height) -->
            <section class="min-h-[calc(100vh-5rem)] flex items-center justify-center relative overflow-hidden bg-gradient-to-b from-qr-bg via-qr-surface/60 to-qr-bg px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
                <!-- Faint Radial Violet Glow Behind the Text -->
                <div class="hero-glow absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[340px] sm:w-[620px] md:w-[860px] h-[340px] sm:h-[520px] bg-qr-accent/20 blur-[130px] rounded-full pointer-events-none -z-0"></div>

                <!-- Secondary ambient glow spots -->
                <div class="absolute -top-24 -left-24 w-80 h-80 bg-qr-accent-dark/15 blur-[120px] rounded-full pointer-events-none -z-0"></div>
                <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-qr-accent/10 blur-[120px] rounded-full pointer-events-none -z-0"></div>

                <div class="max-w-5xl mx-auto text-center relative z-10 flex flex-col items-center">
                    <!-- Status Badge with Colored Dot (Vertep design style) -->
                    <div class="animate-fade-in-up inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full border border-qr-border bg-qr-surface/90 backdrop-blur-sm text-xs font-semibold uppercase tracking-widest text-qr-accent-light mb-8 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-qr-success opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-qr-success"></span>
                        </span>
                        <span>LIVE EVENT PLATFORM 2026</span>
                    </div>

                    <!-- Large Bold Heading (3 Lines) -->
                    <h1 class="animate-fade-in-up anim-delay-100 text-5xl sm:text-7xl md:text-8xl lg:text-9xl font-black tracking-tight uppercase leading-[0.92] text-qr-text select-none text-center">
                        <span class="block tracking-tighter">EVERY EVENT</span>
                        <span class="block tracking-tighter mt-1 sm:mt-2">HAS ITS</span>
                        <span class="block italic text-qr-accent font-black tracking-tight mt-1 sm:mt-2 drop-shadow-[0_0_35px_rgba(139,92,246,0.45)]">STORY.</span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="animate-fade-in-up anim-delay-200 mt-8 text-base sm:text-lg md:text-xl text-qr-muted max-w-2xl mx-auto leading-relaxed text-center font-normal">
                        Find events, buy tickets, see where friends are going — all in one place.
                    </p>

                    <!-- CTA Button: "GO TO EVENTS →" (accent outline) -->
                    <div class="animate-fade-in-up anim-delay-300 mt-10 flex items-center justify-center">
                        <a
                            href="{{ route('events.index') }}"
                            class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-full border-2 border-qr-accent text-qr-accent bg-transparent hover:bg-qr-accent hover:text-white hover:shadow-[0_0_30px_rgba(139,92,246,0.45)] transition-all duration-300 font-bold uppercase tracking-wider text-xs sm:text-sm group"
                        >
                            <span>GO TO EVENTS</span>
                            <span class="transition-transform duration-200 group-hover:translate-x-1.5">→</span>
                        </a>
                    </div>

                    <!-- Scroll Down Indicator -->
                    <a
                        href="#features"
                        class="animate-fade-in-up anim-delay-400 mt-16 text-qr-muted hover:text-qr-accent transition-colors flex flex-col items-center gap-2 group"
                        aria-label="Scroll to features"
                    >
                        <span class="text-[10px] uppercase tracking-widest font-bold">SCROLL</span>
                        <svg class="w-4 h-4 animate-bounce group-hover:text-qr-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </a>
                </div>
            </section>

            <!-- 3. Features Section -->
            <section id="features" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 border-t border-qr-border/60 bg-qr-bg">
                <div class="max-w-7xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20">
                        <p class="text-xs font-bold tracking-widest uppercase text-qr-accent mb-3">WHAT WE OFFER</p>
                        <h2 class="text-3xl sm:text-5xl font-black tracking-tight uppercase text-qr-text">BUILT FOR IMMERSIVE EXPERIENCES</h2>
                        <p class="mt-4 text-sm sm:text-base text-qr-muted max-w-xl mx-auto leading-relaxed">
                            Complete infrastructure for organizers, sponsors, and attendees from entry to stage finale.
                        </p>
                    </div>

                    <!-- 3 Cards in a Row: Ticketing, Booth Rental, Rundown -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                        <!-- Card 1: 🎫 Ticketing -->
                        <div class="bg-qr-card border border-qr-border rounded-2xl p-8 hover:border-qr-accent hover:shadow-[0_0_35px_rgba(139,92,246,0.25)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                            <div class="absolute -top-12 -right-12 w-28 h-28 bg-qr-accent/10 rounded-full blur-2xl group-hover:bg-qr-accent/25 transition-all duration-500"></div>

                            <div>
                                <div class="w-14 h-14 rounded-xl bg-qr-surface border border-qr-border flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:border-qr-accent/50 transition-all duration-300 shadow-inner">
                                    🎫
                                </div>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="w-1.5 h-1.5 rounded-full bg-qr-success"></span>
                                    <span class="text-[11px] font-mono uppercase tracking-wider text-qr-success">Instant Delivery</span>
                                </div>
                                <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-qr-text uppercase">Ticketing</h3>
                                <p class="mt-3 text-sm text-qr-muted leading-relaxed">
                                    Instant QR ticketing, secure anti-scalp verification, and flexible tier pricing designed for rapid crowd check-ins and frictionless entry.
                                </p>
                            </div>

{{--                            <a href="{{ route('tickets') }}" class="mt-8 pt-6 border-t border-qr-border/60 flex items-center justify-between">--}}
{{--                                <span class="text-xs font-bold tracking-wider uppercase text-qr-accent group-hover:underline">Explore Passes</span>--}}
{{--                                <span class="text-qr-accent transition-transform group-hover:translate-x-1 duration-200">→</span>--}}
{{--                            </a>--}}
                        </div>

                        <!-- Card 2: 🏪 Booth Rental -->
                        <div class="bg-qr-card border border-qr-border rounded-2xl p-8 hover:border-qr-accent hover:shadow-[0_0_35px_rgba(139,92,246,0.25)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                            <div class="absolute -top-12 -right-12 w-28 h-28 bg-qr-accent/10 rounded-full blur-2xl group-hover:bg-qr-accent/25 transition-all duration-500"></div>

                            <div>
                                <div class="w-14 h-14 rounded-xl bg-qr-surface border border-qr-border flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:border-qr-accent/50 transition-all duration-300 shadow-inner">
                                    🏪
                                </div>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="w-1.5 h-1.5 rounded-full bg-qr-warning"></span>
                                    <span class="text-[11px] font-mono uppercase tracking-wider text-qr-warning">Interactive Layouts</span>
                                </div>
                                <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-qr-text uppercase">Booth Rental</h3>
                                <p class="mt-3 text-sm text-qr-muted leading-relaxed">
                                    Interactive venue floor plans, transparent booth space leasing, and automated placement for food pop-ups, sponsors, and artist merchants.
                                </p>
                            </div>

{{--                            <a href="{{ route('booths') }}" class="mt-8 pt-6 border-t border-qr-border/60 flex items-center justify-between">--}}
{{--                                <span class="text-xs font-bold tracking-wider uppercase text-qr-accent group-hover:underline">Reserve Booth</span>--}}
{{--                                <span class="text-qr-accent transition-transform group-hover:translate-x-1 duration-200">→</span>--}}
{{--                            </a>--}}
                        </div>

                        <!-- Card 3: 📋 Rundown -->
                        <div class="bg-qr-card border border-qr-border rounded-2xl p-8 hover:border-qr-accent hover:shadow-[0_0_35px_rgba(139,92,246,0.25)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                            <div class="absolute -top-12 -right-12 w-28 h-28 bg-qr-accent/10 rounded-full blur-2xl group-hover:bg-qr-accent/25 transition-all duration-500"></div>

                            <div>
                                <div class="w-14 h-14 rounded-xl bg-qr-surface border border-qr-border flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:border-qr-accent/50 transition-all duration-300 shadow-inner">
                                    📋
                                </div>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="w-1.5 h-1.5 rounded-full bg-qr-info"></span>
                                    <span class="text-[11px] font-mono uppercase tracking-wider text-qr-info">Live Updates</span>
                                </div>
                                <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-qr-text uppercase">Rundown</h3>
                                <p class="mt-3 text-sm text-qr-muted leading-relaxed">
                                    Real-time event timelines, stage schedules, and live notifications keeping attendees in sync with lineup changes and surprise performances.
                                </p>
                            </div>

{{--                            <a href="{{ route('rundown') }}" class="mt-8 pt-6 border-t border-qr-border/60 flex items-center justify-between">--}}
{{--                                <span class="text-xs font-bold tracking-wider uppercase text-qr-accent group-hover:underline">View Timetable</span>--}}
{{--                                <span class="text-qr-accent transition-transform group-hover:translate-x-1 duration-200">→</span>--}}
{{--                            </a>--}}
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. CTA Section -->
            <section id="events" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-qr-bg overflow-hidden">
                <div class="max-w-5xl mx-auto relative">
                    <div class="relative rounded-3xl bg-gradient-to-r from-qr-surface via-qr-card to-qr-surface border border-qr-border p-8 sm:p-16 text-center overflow-hidden shadow-2xl">
                        <!-- Background Glow Accent -->
                        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-qr-accent/20 blur-[110px] rounded-full pointer-events-none"></div>
                        <div class="absolute -bottom-24 right-0 w-72 h-72 bg-qr-accent-dark/20 blur-[100px] rounded-full pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col items-center">
                            <!-- Status Pill -->
                            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full border border-qr-border/80 bg-qr-bg/60 text-xs font-mono uppercase tracking-wider text-qr-accent mb-6">
                                <span class="w-2 h-2 rounded-full bg-qr-accent animate-pulse"></span>
                                <span>GET TOGETHER</span>
                            </div>

                            <!-- CTA Headline -->
                            <h2 class="text-3xl sm:text-5xl md:text-6xl font-black tracking-tight uppercase text-qr-text max-w-2xl leading-tight">
                                Invite a friend — make the night unforgettable!
                            </h2>

                            <!-- Description Text -->
                            <p class="mt-6 text-sm sm:text-base md:text-lg text-qr-muted max-w-xl mx-auto leading-relaxed">
                                Live performances, festival grounds, and late-night memories are made together. Discover the best stages in the city, bring your crowd, and step into the sound.
                            </p>

                            <!-- "GO TO EVENTS ↗" Link -->
                            <div class="mt-10">
{{--                                <a--}}
{{--                                    href="{{ route('events') }}"--}}
{{--                                    class="inline-flex items-center justify-center gap-2.5 px-9 py-4 rounded-full bg-qr-accent hover:bg-qr-accent-dark text-white font-bold uppercase tracking-wider text-xs sm:text-sm shadow-[0_0_25px_rgba(139,92,246,0.45)] hover:shadow-[0_0_40px_rgba(139,92,246,0.7)] transition-all duration-300 group"--}}
{{--                                >--}}
{{--                                    <span>GO TO EVENTS</span>--}}
{{--                                    <span class="transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 text-base">↗</span>--}}
{{--                                </a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- 5. Footer -->
        <footer class="border-t border-qr-border/80 bg-qr-bg py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex flex-col items-center justify-center gap-4 text-center">
                <!-- Footer Brand Mark -->
                <div class="flex items-center gap-2 text-sm font-bold tracking-widest uppercase text-qr-muted">
                    <span class="text-qr-accent">✦</span>
                    <span><span class="text-qr-accent">QR</span>EVEN</span>
                </div>

                <!-- Copyright -->
                <p class="text-xs text-qr-muted tracking-wider">
                    &copy; 2026 Qreven. All rights reserved.
                </p>
            </div>
        </footer>

        <!-- Mobile Menu Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggleBtn = document.getElementById('mobile-menu-toggle');
                const mobileMenu = document.getElementById('mobile-menu');

                if (toggleBtn && mobileMenu) {
                    toggleBtn.addEventListener('click', function () {
                        mobileMenu.classList.toggle('hidden');
                    });
                }
            });
        </script>
    </body>
</html>
