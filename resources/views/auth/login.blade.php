@extends('layouts.auth')

@section('title', 'Masuk Penyelenggara')
@section('heading', 'Masuk sebagai Penyelenggara')
@section('subheading', 'Kelola event, penjualan tiket, pembayaran, dan booth Anda.')

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

    <p class="switch">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>

    <div class="divider"><span>atau</span></div>

    <a href="{{ route('auth.redirect', ['provider' => 'google']) }}" class="btn">
        Lanjutkan dengan Google
    </a>

    <a href="{{ route('auth.redirect', ['provider' => 'github']) }}" class="btn github">
        Lanjutkan dengan GitHub
    </a>
@endsection
