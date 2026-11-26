@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<h1 class="text-3xl font-extrabold tracking-tight text-brand-950">Selamat datang kembali</h1>
<p class="mt-2 text-sm text-slate-500">Masuk untuk mengelola event kamu.</p>

@if(session('status'))
    <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
    @csrf
    <x-field name="email" label="Email">
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input" placeholder="kamu@email.com">
    </x-field>
    <x-field name="password" label="Kata sandi">
        <input id="password" name="password" type="password" required autocomplete="current-password" class="input" placeholder="••••••••">
    </x-field>

    <label class="flex items-center gap-2 text-sm text-slate-600">
        <input type="checkbox" name="remember" class="size-4 rounded border-brand-300 text-brand-600 focus:ring-brand-200"> Ingat saya
    </label>

    <button type="submit" class="btn-primary w-full py-3">Masuk</button>
</form>

<div class="my-6 flex items-center gap-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
    <span class="h-px flex-1 bg-brand-100"></span> atau <span class="h-px flex-1 bg-brand-100"></span>
</div>

<div class="grid grid-cols-2 gap-3">
    <a href="{{ url('/auth/google/redirect') }}" class="btn-secondary">
        <svg class="size-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M23 12.3c0-.8-.1-1.5-.2-2.2H12v4.2h6.2a5.3 5.3 0 0 1-2.3 3.5v2.9h3.7c2.2-2 3.4-5 3.4-8.4z"/><path fill="#34A853" d="M12 24c3.1 0 5.7-1 7.6-2.8l-3.7-2.9c-1 .7-2.3 1.1-3.9 1.1-3 0-5.5-2-6.4-4.7H1.8v3A12 12 0 0 0 12 24z"/><path fill="#FBBC05" d="M5.6 14.7a7.2 7.2 0 0 1 0-4.6v-3H1.8a12 12 0 0 0 0 10.6z"/><path fill="#EA4335" d="M12 4.8c1.7 0 3.2.6 4.4 1.7l3.3-3.3A12 12 0 0 0 1.8 7.1l3.8 3C6.5 6.8 9 4.8 12 4.8z"/></svg>
        Google
    </a>
    <a href="{{ url('/auth/github/redirect') }}" class="btn-secondary">
        <svg class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
        GitHub
    </a>
</div>

@if(Route::has('register'))
<p class="mt-8 text-center text-sm text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-800">Daftar</a></p>
@endif
@endsection
