<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket · {{ $event->nama_event }} · {{ config('app.name', 'Qreven') }}</title>
    <meta name="description" content="Daftar tiket resmi untuk {{ $event->nama_event }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,600,700|jetbrains-mono:400,500,600&display=swap" rel="stylesheet">
    <x-theme-init />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.event-style')
</head>
<body class="min-h-screen bg-canvas text-ink">

<header class="border-b border-line bg-surface">
    <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-6">
        <a href="{{ route('event.home', ['event_subdomain' => $event->subdomain]) }}" class="flex items-center gap-2.5">
            <span class="grid size-8 place-items-center rounded border border-ink bg-accent text-ink">
                <x-icon name="qr" class="size-4" />
            </span>
            <span class="font-display text-base font-bold tracking-tight text-ink">{{ config('app.name', 'Qreven') }}</span>
        </a>
        <div class="flex items-center gap-2">
            <x-theme-toggle />
            <a href="{{ route('event.home', ['event_subdomain' => $event->subdomain]) }}" class="btn-secondary btn-sm">
                <x-icon name="back" class="size-3.5" /> Detail Event
            </a>
        </div>
    </div>
</header>

<section class="panel-dark border-b">
    <div class="mx-auto max-w-5xl px-6 py-10 sm:py-12">
        <div class="flex flex-wrap items-center gap-2 font-mono text-xs text-white/80">
            <span class="inline-flex items-center gap-1.5 rounded border border-white/20 px-2.5 py-1">
                <x-icon name="calendar" class="size-3.5 text-[#D9F478]" />
                <x-date :value="$event->tanggal_mulai ?? null" format="l, d F Y" />
            </span>
            <span class="inline-flex items-center gap-1.5 rounded border border-white/20 px-2.5 py-1">
                <x-icon name="clock" class="size-3.5 text-[#D9F478]" />
                <x-date :value="$event->tanggal_mulai ?? null" format="H:i" /> {{ $event->timezone ?? 'WIB' }}
            </span>
            @if(!empty($event->lokasi))
                <span class="inline-flex items-center gap-1.5 rounded border border-white/20 px-2.5 py-1">
                    <x-icon name="pin" class="size-3.5 text-[#D9F478]" />
                    {{ $event->lokasi }}
                </span>
            @endif
        </div>
        <h1 class="font-display mt-5 text-3xl font-bold tracking-tight text-white sm:text-5xl">{{ $event->nama_event }}</h1>
        <p class="mt-2 text-sm text-white/70">Daftar kategori tiket yang tersedia untuk event ini.</p>
    </div>
</section>

<main class="mx-auto max-w-5xl px-6 py-10">
    <div class="card card-pad">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line pb-4">
            <div>
                <h2 class="font-display text-lg font-bold text-ink">Daftar Tiket</h2>
                <p class="text-xs text-muted">{{ $ticketTypes->count() }} kategori tiket tersedia</p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            @forelse($ticketTypes as $ticket)
                @php($available = $ticket->kuota > 0)
                <div class="flex flex-col justify-between rounded-md border {{ $available ? 'border-ink bg-surface' : 'border-line bg-canvas/60' }} p-5">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-display text-base font-bold text-ink">{{ $ticket->nama_tiket }}</h3>
                            <span class="badge {{ $available ? 'badge-green' : 'badge-red' }}">
                                {{ $available ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                        <p class="font-display mt-3 text-2xl font-bold tracking-tight text-ink">
                            <x-money :value="$ticket->harga ?? 0" />
                        </p>
                    </div>

                    <div class="mt-5 flex items-center justify-between border-t border-line pt-3 font-mono text-xs text-muted">
                        <span>Kuota tersisa: <strong class="text-ink">{{ $ticket->kuota }}</strong>@if($ticket->capacity) / {{ $ticket->capacity }}@endif</span>
                        @if(! $available)
                            <span class="font-semibold text-error">Stok habis</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2">
                    <x-empty icon="ticket" title="Tiket belum tersedia" message="Penyelenggara belum menambahkan kategori tiket untuk event ini." />
                </div>
            @endforelse
        </div>
    </div>
</main>

<footer class="border-t border-line bg-surface py-8 text-center font-mono text-xs text-muted">
    Dikelola dengan {{ config('app.name', 'Qreven') }}
</footer>
</body>
</html>
