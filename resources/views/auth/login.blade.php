@extends('layouts.auth')

@section('title', 'Masuk Penyelenggara')
@section('heading', 'Masuk sebagai Penyelenggara')
@section('subheading', 'Gunakan akun Anda untuk membuka dashboard Qreven.')
@section('auth-body-class', 'qreven-login')

@section('auth-brand')
    <aside class="login-brand" aria-label="Qreven">
        <span class="login-wordmark">Qreven<span>.</span></span>
        <div class="login-ticket" aria-hidden="true">
            <div class="login-ticket-art"></div>
            <div class="login-ticket-stub"><span></span></div>
        </div>
        <p>Kelola event, tiket, dan booth dalam satu dashboard.</p>
    </aside>
@endsection

@push('auth-styles')
    <style>
        .qreven-login {
            --login-bg: #0b0b14;
            --login-panel: #14142b;
            --login-line: #383552;
            --login-text: #faf8ff;
            --login-muted: #b5b1cb;
            --login-accent: #c4b5fd;
            display: grid;
            grid-template-columns: minmax(0, 23rem) minmax(0, 30rem);
            gap: clamp(2rem, 7vw, 7rem);
            padding: clamp(1.25rem, 4vw, 4rem);
            background: var(--login-bg);
            color: var(--login-text);
            color-scheme: dark;
        }
        .qreven-login .login-brand { min-width: 0; }
        .qreven-login .login-wordmark { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.055em; }
        .qreven-login .login-wordmark span { color: var(--login-accent); }
        .qreven-login .login-brand p {
            max-width: 13em;
            margin: 0;
            font-size: clamp(1.65rem, 3vw, 2.65rem);
            font-weight: 680;
            line-height: 1.12;
            letter-spacing: -0.045em;
            text-wrap: balance;
        }
        .qreven-login .login-ticket {
            display: grid;
            grid-template-columns: 1fr 25%;
            height: clamp(8rem, 15vw, 12rem);
            margin: 2.25rem 0;
            overflow: hidden;
            transform: rotate(-7deg);
            border: 1px solid #9b7fef;
            border-radius: 1.15rem;
            background: #29204c;
            box-shadow: 0 1.4rem 4rem #6d28d94d;
        }
        .qreven-login .login-ticket-art {
            background:
                radial-gradient(ellipse at 22% 15%, #c4b5fd, transparent 62%),
                repeating-linear-gradient(135deg, #c4b5fd36 0 1px, transparent 1px 9px),
                linear-gradient(135deg, #8054ec, #251b4c 82%);
        }
        .qreven-login .login-ticket-stub { display: grid; place-items: center; border-left: 1px dashed #a594d1; }
        .qreven-login .login-ticket-stub span {
            width: 50%;
            height: 66%;
            background: repeating-linear-gradient(0deg, #c4b5fd 0 2px, transparent 2px 4px, #c4b5fd 4px 8px, transparent 8px 13px);
        }
        .qreven-login .card {
            max-width: none;
            min-width: 0;
            padding: clamp(1.25rem, 3.8vw, 2.5rem);
            border-color: var(--login-line);
            border-radius: 1.4rem;
            background: var(--login-panel);
            box-shadow: 0 1.25rem 4rem #0005;
        }
        .qreven-login h1 { font-size: clamp(1.5rem, 3.2vw, 2.15rem); line-height: 1.12; letter-spacing: -0.045em; }
        .qreven-login .sub, .qreven-login .switch, .qreven-login .divider { color: var(--login-muted); }
        .qreven-login .error { background: #381f37; color: #ffc6d2; }
        .qreven-login .field input {
            min-height: 2.9rem;
            border-color: var(--login-line);
            background: var(--login-bg);
            color: var(--login-text);
            font-size: 1rem;
        }
        .qreven-login .checkbox input { accent-color: #a78bfa; }
        .qreven-login .btn {
            min-height: 2.9rem;
            margin: 0;
            border-color: var(--login-line);
            background: #201e39;
            color: var(--login-text);
        }
        .qreven-login .btn:hover { background: #2e294a; }
        .qreven-login .btn.primary { border-color: #a78bfa; background: #a78bfa; color: #160b34; }
        .qreven-login .btn.primary:hover { background: var(--login-accent); }
        .qreven-login :is(a, button, input):focus-visible { outline: 2px solid var(--login-accent); outline-offset: 3px; }
        .qreven-login .divider { margin: 1.25rem 0 0.9rem; }
        .qreven-login .divider::before, .qreven-login .divider::after { background: var(--login-line); }
        .qreven-login .login-providers { display: grid; gap: 0.6rem; }
        .qreven-login .switch { margin-top: 1.35rem; }
        .qreven-login .switch a { color: var(--login-accent); }

        @media (max-width: 48rem) {
            .qreven-login { grid-template-columns: minmax(0, 30rem); gap: 1.25rem; align-content: center; }
            .qreven-login .login-ticket, .qreven-login .login-brand p { display: none; }
        }
    </style>
@endpush

@section('content')
    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
        </div>

        <label class="checkbox">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            Ingat saya
        </label>

        <button type="submit" class="btn primary">Masuk</button>
    </form>

    <div class="divider"><span>atau</span></div>

    <div class="login-providers">
        <a href="{{ route('auth.redirect', ['provider' => 'google']) }}" class="btn">
            Lanjutkan dengan Google
        </a>

        <a href="{{ route('auth.redirect', ['provider' => 'github']) }}" class="btn">
            Lanjutkan dengan GitHub
        </a>
    </div>

    <p class="switch">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
@endsection
