@extends('layouts.auth')

@section('title', 'Daftar Penyelenggara')
@section('heading', 'Daftar sebagai Penyelenggara')
@section('subheading', 'Buat akun untuk mulai mengelola event, tiket, pembayaran, dan booth.')

@section('content')
    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="field">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
        </div>

        <div class="field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn primary">Daftar</button>
    </form>

    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>

    <div class="divider"><span>atau</span></div>

    <a href="{{ route('auth.redirect', ['provider' => 'google']) }}" class="btn">
        Lanjutkan dengan Google
    </a>

    <a href="{{ route('auth.redirect', ['provider' => 'github']) }}" class="btn github">
        Lanjutkan dengan GitHub
    </a>
@endsection
