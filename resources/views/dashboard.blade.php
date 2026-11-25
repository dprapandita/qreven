<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dashboard Penyelenggara - {{ config('app.name', 'Laravel') }}</title>

        <style>
            * { box-sizing: border-box; }
            body {
                margin: 0;
                min-height: 100vh;
                padding: 2rem 1.5rem;
                background: #f5f5f4;
                color: #1c1917;
                font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            }
            .wrap { max-width: 48rem; margin: 0 auto; }
            .card {
                padding: 2rem;
                background: #ffffff;
                border: 1px solid #e7e5e4;
                border-radius: 0.75rem;
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.08);
            }
            h1 { margin: 0 0 0.5rem; font-size: 1.5rem; }
            p { margin: 0 0 1.5rem; color: #57534e; font-size: 0.9rem; }
            .avatar { width: 3rem; height: 3rem; border-radius: 9999px; object-fit: cover; margin-bottom: 1rem; }
            button {
                padding: 0.6rem 1rem;
                border: 1px solid #d6d3d1;
                border-radius: 0.5rem;
                background: #ffffff;
                color: #1c1917;
                font-size: 0.9rem;
                font-weight: 600;
                cursor: pointer;
            }
            button:hover { background: #f5f5f4; }
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="card">
                @if (auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="avatar">
                @endif

                <h1>Dashboard Penyelenggara</h1>
                <p>Selamat datang, {{ auth()->user()->name }} ({{ auth()->user()->email }}).</p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Keluar</button>
                </form>
            </div>
        </div>
    </body>
</html>
