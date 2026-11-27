@extends('layouts.auth')
@section('title', 'Daftar')

@section('content')
<div>
    <p class="eyebrow">Pendaftaran Penyelenggara</p>
    <h1 class="font-display mt-2 text-3xl font-bold tracking-tight text-ink">Buat akun baru</h1>
    <p class="mt-2 text-sm text-muted">Mulai kelola event kamu dalam hitungan menit.</p>
</div>

<form method="POST" action="{{ route('register') }}" autocomplete="off" class="mt-8 space-y-5">
    @csrf
    <x-field name="name" label="Nama lengkap">
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="off" class="input" placeholder="Nama kamu">
    </x-field>
    <x-field name="email" label="Email">
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="off" class="input" placeholder="kamu@email.com">
    </x-field>
    <div class="grid gap-5 sm:grid-cols-2">
        <x-field name="password" label="Kata sandi">
            <input id="password" name="password" type="password" required autocomplete="off" class="input" placeholder="Min. 8 karakter">
        </x-field>
        <x-field name="password_confirmation" label="Ulangi kata sandi">
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="off" class="input" placeholder="Ulangi sandi">
        </x-field>
    </div>

    <button type="submit" class="btn-accent w-full py-2.5">Daftar</button>
</form>

<div class="my-6 flex items-center gap-3 font-mono text-[11px] uppercase tracking-wider text-muted">
    <span class="h-px flex-1 bg-line"></span> atau <span class="h-px flex-1 bg-line"></span>
</div>

<div class="grid gap-3 sm:grid-cols-2">
    <a href="{{ route('auth.redirect', ['provider' => 'google']) }}" class="btn-secondary">Google</a>
    <a href="{{ route('auth.redirect', ['provider' => 'github']) }}" class="btn-secondary">GitHub</a>
</div>

@if(Route::has('login'))
<p class="mt-8 text-center text-sm text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-ink underline underline-offset-4 hover:text-muted">Masuk</a></p>
@endif
@endsection
