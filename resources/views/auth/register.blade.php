@extends('layouts.auth')
@section('title', 'Daftar')

@section('content')
<h1 class="text-3xl font-extrabold tracking-tight text-brand-950">Buat akun baru</h1>
<p class="mt-2 text-sm text-slate-500">Mulai kelola event kamu dalam semenit.</p>

<form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
    @csrf
    <x-field name="name" label="Nama lengkap">
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="input" placeholder="Nama kamu">
    </x-field>
    <x-field name="email" label="Email">
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="input" placeholder="kamu@email.com">
    </x-field>
    <div class="grid gap-5 sm:grid-cols-2">
        <x-field name="password" label="Kata sandi">
            <input id="password" name="password" type="password" required autocomplete="new-password" class="input" placeholder="Min. 8 karakter">
        </x-field>
        <x-field name="password_confirmation" label="Ulangi kata sandi">
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="input" placeholder="Ulangi sandi">
        </x-field>
    </div>

    <button type="submit" class="btn-primary w-full py-3">Daftar</button>
</form>

<div class="my-6 flex items-center gap-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
    <span class="h-px flex-1 bg-brand-100"></span> atau <span class="h-px flex-1 bg-brand-100"></span>
</div>

<div class="grid grid-cols-2 gap-3">
    <a href="{{ url('/auth/google/redirect') }}" class="btn-secondary">Google</a>
    <a href="{{ url('/auth/github/redirect') }}" class="btn-secondary">GitHub</a>
</div>

@if(Route::has('login'))
<p class="mt-8 text-center text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-800">Masuk</a></p>
@endif
@endsection
