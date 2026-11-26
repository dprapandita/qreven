@extends('layouts.app')
@section('title', 'Daftar event')
@section('content')
<style>
.qcrud{max-width:68rem;margin:auto;background:#fafbf7;color:#192a23;padding:clamp(1rem,3vw,2.5rem);border:1px solid #d4d9d1;font:normal 1rem/1.5 system-ui,sans-serif}.qcrud h1{font-size:clamp(1.6rem,4vw,2.3rem);font-weight:700;letter-spacing:-.04em;margin:.5rem 0 1.25rem}.qcrud a,.qcrud summary{color:#18523c;text-decoration:underline;text-underline-offset:3px;cursor:pointer}.qcrud article,.qcrud details{padding:1rem 0;border-top:1px solid #cdd5cc}.qcrud h2{font-weight:700;font-size:1.1rem}.qcrud label{display:grid;gap:.3rem;font-size:.9rem;font-weight:600}.qcrud .gridfields,.qcrud dl{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,15rem),1fr));gap:1rem;margin:1rem 0}.qcrud input,.qcrud textarea{width:100%;padding:.6rem;background:white;color:#192a23;border:1px solid #9ca99e;border-radius:4px;font:inherit}.qcrud textarea{min-height:5rem}.qcrud button{padding:.55rem 1rem;background:#174a36;color:white;border:0;border-radius:4px;cursor:pointer}.qcrud .remove{background:transparent;color:#962d25;padding:.4rem 0}.qcrud dt{font-size:.8rem;color:#526257}.qcrud dd{margin:0;overflow-wrap:anywhere}.qcrud [role=alert]{padding:.85rem;background:#ffebe4;color:#78281f}.qcrud [role=status]{padding:.85rem;background:#dcefe1}.qcrud :focus-visible{outline:2px solid #37745b;outline-offset:2px}
</style>
<section class="qcrud">
<h1>Daftar event</h1>
@if ($errors->any())
<div role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
@if (session('success'))<p role="status">{{ session('success') }}</p>@endif
@if (Route::has('events.create'))
<a href="{{ route('events.create') }}">Tambah event</a>
@else
<details @if (old('_form') === 'create') open @endif><summary>Tambah event</summary>
<form method="POST" action="{{ route('events.store') }}">
@csrf
<input type="hidden" name="_form" value="{{ 'create' }}">
<div class="gridfields">
<label>Nama event<input type="text" name="nama_event" value="{{ old('_form') === 'create' ? old('nama_event') : '' }}" required></label>
<label>Lokasi<input type="text" name="lokasi" value="{{ old('_form') === 'create' ? old('lokasi') : '' }}" required></label>
<label>Mulai<input type="datetime-local" name="tanggal_mulai" value="{{ old('_form') === 'create' ? old('tanggal_mulai') : '' }}" required></label>
<label>Selesai<input type="datetime-local" name="tanggal_selesai" value="{{ old('_form') === 'create' ? old('tanggal_selesai') : '' }}" required></label>
<label>Deskripsi<textarea name="deskripsi" required>{{ old('_form') === 'create' ? old('deskripsi') : '' }}</textarea></label>
<label>Subdomain<input type="text" name="subdomain" value="{{ old('_form') === 'create' ? old('subdomain') : '' }}"></label>
</div>
<button type="submit">Simpan</button>
</form>
</details>
@endif
@forelse ($events as $event)
<article><h2>{{ $event->nama_event }}</h2>
<dl><div><dt>Lokasi</dt><dd>{{ $event->lokasi ?? '—' }}</dd></div><div><dt>Mulai</dt><dd>{{ $event->tanggal_mulai ?? '—' }}</dd></div><div><dt>Selesai</dt><dd>{{ $event->tanggal_selesai ?? '—' }}</dd></div></dl>
<a href="{{ route('events.show', [$event]) }}">Detail event</a>
@if (Route::has('events.edit'))
<a href="{{ route('events.edit', [$event]) }}">Edit</a>
@else
<details @if (old('_form') === (string) $event->getKey()) open @endif><summary>Edit</summary>
<form method="POST" action="{{ route('events.update', [$event]) }}">
@csrf
@method('PUT')
<input type="hidden" name="_form" value="{{ (string) $event->getKey() }}">
<div class="gridfields">
<label>Nama event<input type="text" name="nama_event" value="{{ old('_form') === (string) $event->getKey() ? old('nama_event') : $event->nama_event }}" required></label>
<label>Lokasi<input type="text" name="lokasi" value="{{ old('_form') === (string) $event->getKey() ? old('lokasi') : $event->lokasi }}" required></label>
<label>Mulai<input type="datetime-local" name="tanggal_mulai" value="{{ old('_form') === (string) $event->getKey() ? old('tanggal_mulai') : ($event->tanggal_mulai ? \Illuminate\Support\Carbon::parse($event->tanggal_mulai)->format('Y-m-d\TH:i') : '') }}" required></label>
<label>Selesai<input type="datetime-local" name="tanggal_selesai" value="{{ old('_form') === (string) $event->getKey() ? old('tanggal_selesai') : ($event->tanggal_selesai ? \Illuminate\Support\Carbon::parse($event->tanggal_selesai)->format('Y-m-d\TH:i') : '') }}" required></label>
<label>Deskripsi<textarea name="deskripsi" required>{{ old('_form') === (string) $event->getKey() ? old('deskripsi') : $event->deskripsi }}</textarea></label>
<label>Subdomain<input type="text" name="subdomain" value="{{ old('_form') === (string) $event->getKey() ? old('subdomain') : $event->subdomain }}"></label>
</div>
<button type="submit">Simpan perubahan</button>
</form>
</details>
@endif
<form method="POST" action="{{ route('events.destroy', [$event]) }}" onsubmit="return confirm('Hapus data ini?')">
@csrf
@method('DELETE')
<button type="submit" class="remove">Hapus</button>
</form></article>
@empty
<p>Belum ada event.</p>
@endforelse
@if ($events instanceof \Illuminate\Contracts\Pagination\Paginator)
{{ $events->links() }}
@endif
</section>
@endsection
