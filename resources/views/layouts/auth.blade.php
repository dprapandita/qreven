<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Penyelenggara') - {{ config('app.name', 'Laravel') }}</title>

        <style>
            * { box-sizing: border-box; }
            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1.5rem;
                background: #f5f5f4;
                color: #1c1917;
                font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            }
            .card {
                width: 100%;
                max-width: 26rem;
                padding: 2rem;
                background: #ffffff;
                border: 1px solid #e7e5e4;
                border-radius: 0.75rem;
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.08);
            }
            h1 { margin: 0 0 0.5rem; font-size: 1.25rem; }
            .sub { margin: 0 0 1.5rem; color: #57534e; font-size: 0.875rem; line-height: 1.5; }
            .error {
                margin-bottom: 1rem;
                padding: 0.75rem;
                border-radius: 0.5rem;
                background: #fef2f2;
                color: #b91c1c;
                font-size: 0.875rem;
            }
            .error ul { margin: 0; padding-left: 1.1rem; }
            .field { margin-bottom: 1rem; }
            .field label { display: block; margin-bottom: 0.35rem; font-size: 0.85rem; font-weight: 600; }
            .field input {
                width: 100%;
                padding: 0.6rem 0.75rem;
                border: 1px solid #d6d3d1;
                border-radius: 0.5rem;
                font-size: 0.95rem;
                font-family: inherit;
            }
            .field input:focus { outline: 2px solid #a8a29e; border-color: #a8a29e; }
            .checkbox {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1rem;
                font-size: 0.875rem;
                font-weight: 500;
            }
            .checkbox input { width: auto; }
            .btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                margin-bottom: 0.75rem;
                padding: 0.7rem 1rem;
                border: 1px solid #d6d3d1;
                border-radius: 0.5rem;
                background: #ffffff;
                color: #1c1917;
                font-size: 0.9rem;
                font-family: inherit;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
                transition: background 0.15s ease;
            }
            .btn:hover { background: #f5f5f4; }
            .btn.primary, .btn.github { background: #1c1917; border-color: #1c1917; color: #ffffff; }
            .btn.primary:hover, .btn.github:hover { background: #292524; }
            .divider {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin: 1.5rem 0 1rem;
                color: #a8a29e;
                font-size: 0.8rem;
            }
            .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: #e7e5e4; }
            .switch { margin: 1rem 0 0; text-align: center; font-size: 0.875rem; color: #57534e; }
            .switch a { color: #1c1917; font-weight: 600; text-underline-offset: 2px; }
        </style>
    </head>
    <body>
        <main class="card">
            <h1>@yield('heading')</h1>
            <p class="sub">@yield('subheading')</p>

            @if ($errors->any())
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </body>
</html>
