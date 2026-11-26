@extends('layouts.auth')

@section('title', 'Daftar Penyelenggara')
@section('heading', 'Daftar sebagai Penyelenggara')
@section('subheading', 'Buat akun untuk mulai mengelola event, tiket, pembayaran, dan booth.')

@section('error-heading', 'Tidak bisa mendaftar')

@section('content')
    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="field">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" @error('name') aria-invalid="true" aria-describedby="auth-errors" @enderror>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" @error('email') aria-invalid="true" aria-describedby="auth-errors" @enderror>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" @error('password') aria-invalid="true" aria-describedby="auth-errors" @enderror>
        </div>

        <div class="field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" @error('password') aria-invalid="true" aria-describedby="auth-errors" @enderror>
        </div>

        <button type="submit" class="btn primary">Daftar</button>
    </form>

    <div class="divider"><span>atau</span></div>

    <div class="auth-providers">
        <a href="{{ route('auth.redirect', ['provider' => 'google']) }}" class="btn">
            Lanjutkan dengan Google
        </a>

        <a href="{{ route('auth.redirect', ['provider' => 'github']) }}" class="btn">
            Lanjutkan dengan GitHub
        </a>
    </div>

    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
@endsection
