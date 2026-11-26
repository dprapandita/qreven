@php use Illuminate\Support\Carbon; @endphp
    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Penyelenggara — Qreven</title>
    <style>
        :root {
            color-scheme: dark;
            --background: #0b0b14;
            --surface: #14142b;
            --line: #34314e;
            --text: #faf8ff;
            --muted: #b8b5cd;
            --accent: #c4b5fd;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            color: var(--text);
            background: var(--background);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        a, button {
            color: inherit;
        }

        a:focus-visible, button:focus-visible, [tabindex]:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 4px;
        }

        .shell {
            display: grid;
            grid-template-columns: 15rem minmax(0, 1fr);
            min-height: 100vh;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            padding: 2.25rem 1.25rem;
            border-right: 1px solid var(--line);
            background: #101020;
        }

        .wordmark {
            padding-inline: 0.75rem;
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -0.055em;
        }

        .wordmark span {
            color: var(--accent);
        }

        .navigation {
            display: grid;
            gap: 0.35rem;
        }

        .navigation a {
            padding: 0.75rem;
            border-radius: 0.65rem;
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
        }

        .navigation a:hover, .navigation [aria-current] {
            background: #22203c;
            color: var(--text);
        }

        .account {
            display: grid;
            gap: 0.9rem;
            margin-top: auto;
        }

        .identity {
            display: flex;
            gap: 0.7rem;
            align-items: center;
            min-width: 0;
        }

        .identity strong {
            font-size: 0.88rem;
            overflow-wrap: anywhere;
        }

        .avatar {
            display: grid;
            flex: 0 0 2.2rem;
            width: 2.2rem;
            height: 2.2rem;
            place-items: center;
            object-fit: cover;
            border-radius: 50%;
            background: #2b2546;
            color: var(--accent);
            font-weight: 750;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.65rem;
            padding: 0.55rem 0.9rem;
            border: 1px solid var(--line);
            border-radius: 0.65rem;
            background: transparent;
            font: inherit;
            font-size: 0.85rem;
            font-weight: 650;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover {
            background: #282442;
        }

        .button.primary {
            border-color: var(--accent);
            background: var(--accent);
            color: #180e34;
        }

        .button.primary:hover {
            background: #ddd6fe;
        }

        .workspace {
            width: min(100%, 82rem);
            margin-inline: auto;
            padding: clamp(1.25rem, 4vw, 4rem);
        }

        .intro {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            padding: clamp(1.4rem, 4vw, 3rem);
            border: 1px solid #514374;
            border-radius: 1.4rem;
            background: radial-gradient(ellipse at top right, #3e286c, transparent 72%), #171428;
        }

        .intro h1 {
            margin: 0;
            font-size: clamp(1.85rem, 4vw, 3.1rem);
            letter-spacing: -0.055em;
            line-height: 1.1;
        }

        .intro p, .empty p {
            margin: 0.55rem 0 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .intro .button {
            flex-shrink: 0;
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
            margin: 1rem 0 1.75rem;
        }

        .metric, .panel {
            border: 1px solid var(--line);
            border-radius: 1rem;
            background: var(--surface);
        }

        .metric {
            display: grid;
            gap: 0.55rem;
            padding: 1rem 1.1rem;
        }

        .metric-label {
            color: var(--muted);
            font-size: 0.86rem;
        }

        .metric-value {
            margin: 0;
            font-size: 2rem;
            font-weight: 750;
            font-variant-numeric: tabular-nums;
        }

        .panel-header {
            padding: 1.15rem;
        }

        .panel-header h2 {
            margin: 0;
            font-size: 1.1rem;
        }

        .table-scroll {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th, td {
            padding: 0.85rem 1.15rem;
            text-align: left;
            border-top: 1px solid var(--line);
        }

        th {
            color: var(--muted);
            font-weight: 600;
        }

        td:first-child {
            overflow-wrap: anywhere;
        }

        td a {
            color: var(--accent);
            text-underline-offset: 0.2rem;
        }

        .status {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border: 1px solid var(--line);
            border-radius: 10rem;
            color: var(--muted);
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .status[data-status="aktif"], .status[data-status="published"] {
            color: #a7f3d0;
            border-color: #36584f;
        }

        .status[data-status="draft"] {
            color: #fcdba5;
            border-color: #64503d;
        }

        .empty {
            padding: 1.5rem;
            border-top: 1px solid var(--line);
        }

        .empty strong {
            font-size: 1rem;
        }

        @media (max-width: 58rem) {
            .shell {
                grid-template-columns: minmax(0, 1fr);
            }

            .sidebar {
                flex-direction: row;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.7rem 1rem;
                padding: 1rem;
                border-right: 0;
                border-bottom: 1px solid var(--line);
            }

            .wordmark {
                padding: 0;
            }

            .navigation {
                display: flex;
                flex-wrap: wrap;
            }

            .account {
                display: flex;
                align-items: center;
                margin: 0 0 0 auto;
            }

            .identity {
                display: none;
            }
        }

        @media (max-width: 36rem) {
            .intro {
                align-items: flex-start;
                flex-direction: column;
            }

            .metrics {
                grid-template-columns: minmax(0, 1fr);
            }

            .metric {
                grid-template-columns: minmax(0, 1fr) auto;
                align-items: center;
            }

            .metric-value {
                font-size: 1.55rem;
            }

            th, td {
                padding-inline: 0.85rem;
            }
        }
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <span class="wordmark">Qreven<span>.</span></span>

        <nav class="navigation" aria-label="Navigasi penyelenggara">
            <a href="{{ route('dashboard') }}" aria-current="page">Dashboard</a>
            @if (Route::has('events.index'))
                <a href="{{ route('events.index') }}">Kelola event</a>
            @endif
        </nav>

        <div class="account">
            <div class="identity">
                @if (auth()->user())
                    <img class="avatar" src="{{ auth()->user()->avatar }}" alt="" referrerpolicy="no-referrer">
                @else
                    <span class="avatar"
                          aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
                <strong>{{ auth()->user()->name }}</strong>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="button" type="submit">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="workspace">
        <header class="intro">
            <div>
                <h1>Dashboard Penyelenggara</h1>
                <p>Selamat datang, {{ auth()->user()->name }}.</p>
            </div>
            @if (Route::has('events.index'))
                <a class="button primary" href="{{ route('events.index') }}">Buat event</a>
            @endif
        </header>

        <dl class="metrics" aria-label="Ringkasan event">
                            <div class="metric">
                                <dt class="metric-label">Total event</dt>
                                <dd class="metric-value">{{ number_format($eventCount, 0, ',', '.') }}</dd>
                            </div>
                            <div class="metric">
                                <dt class="metric-label">Pesanan menunggu pembayaran</dt>
                                <dd class="metric-value">{{ number_format($pendingOrderCount, 0, ',', '.') }}</dd>
                            </div>
                            <div class="metric">
                                <dt class="metric-label">Total booth</dt>
                                <dd class="metric-value">{{ number_format($boothCount, 0, ',', '.') }}</dd>
                            </div>
        </dl>

        <section class="panel" aria-labelledby="recent-events">
            <header class="panel-header">
                <h2 id="recent-events">Event terbaru</h2>
            </header>

            @if ($recentEvents->isEmpty())
                <div class="empty">
                    <strong>Belum ada event</strong>
                    <p>Event yang kamu buat akan muncul di sini.</p>
                </div>
            @else
                <div class="table-scroll" tabindex="0" role="region" aria-label="Daftar event terbaru">
                    <table>
                        <thead>
                        <tr>
                            <th scope="col">Nama event</th>
                            <th scope="col">Tanggal mulai</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($recentEvents as $event)
                            <tr>
                                <td>
                                    @if (Route::has('events.index') && Route::has('events.show'))
                                        <a href="{{ route('events.show', [$event->getRouteKey()]) }}">{{ $event->nama_event }}</a>
                                    @else
                                        {{ $event->nama_event }}
                                    @endif
                                </td>
                                <td>{{ Carbon::parse($event->tanggal_mulai)->locale('id')->translatedFormat('j M Y') }}</td>
                                <td>
                                            <span class="status" data-status="{{ $event->status }}">
                                                {{ match ($event->status) {
                                                    'draft' => 'Draf',
                                                    'aktif' => 'Aktif',
                                                    'published' => 'Terbit',
                                                    'upcoming' => 'Akan datang',
                                                    default => ucfirst($event->status),
                                                } }}
                                            </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </main>
</div>
</body>
</html>
