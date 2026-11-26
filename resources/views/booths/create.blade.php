@extends('layouts.app')
@section('title', 'Tambah booth')
@section('content')
<style>
.qcrud{max-width:68rem;margin:auto;background:#fafbf7;color:#192a23;padding:clamp(1rem,3vw,2.5rem);border:1px solid #d4d9d1;font:normal 1rem/1.5 system-ui,sans-serif}.qcrud h1{font-size:clamp(1.6rem,4vw,2.3rem);font-weight:700;letter-spacing:-.04em;margin:.5rem 0 1.25rem}.qcrud a,.qcrud summary{color:#18523c;text-decoration:underline;text-underline-offset:3px;cursor:pointer}.qcrud article,.qcrud details{padding:1rem 0;border-top:1px solid #cdd5cc}.qcrud h2{font-weight:700;font-size:1.1rem}.qcrud label{display:grid;gap:.3rem;font-size:.9rem;font-weight:600}.qcrud .gridfields,.qcrud dl{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,15rem),1fr));gap:1rem;margin:1rem 0}.qcrud input,.qcrud textarea{width:100%;padding:.6rem;background:white;color:#192a23;border:1px solid #9ca99e;border-radius:4px;font:inherit}.qcrud textarea{min-height:5rem}.qcrud button{padding:.55rem 1rem;background:#174a36;color:white;border:0;border-radius:4px;cursor:pointer}.qcrud .remove{background:transparent;color:#962d25;padding:.4rem 0}.qcrud dt{font-size:.8rem;color:#526257}.qcrud dd{margin:0;overflow-wrap:anywhere}.qcrud [role=alert]{padding:.85rem;background:#ffebe4;color:#78281f}.qcrud [role=status]{padding:.85rem;background:#dcefe1}.qcrud :focus-visible{outline:2px solid #37745b;outline-offset:2px}
</style>
<section class="qcrud">
<h1>Tambah booth</h1>
@if ($errors->any())
<div role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
@if (session('success'))<p role="status">{{ session('success') }}</p>@endif
<a href="{{ route('booths.index', [$event]) }}">Kembali ke daftar</a>
<form method="POST" action="{{ route('booths.store', [$event]) }}">
@csrf
<input type="hidden" name="_form" value="{{ 'create' }}">
<input type="hidden" name="event_id" value="{{ $event->getKey() }}">
<div class="gridfields">
<label>Nama booth<input type="text" name="nama_booth" value="{{ old('_form') === 'create' ? old('nama_booth') : '' }}" required></label>
<label>Harga (Rp)<input type="number" name="harga" value="{{ old('_form') === 'create' ? old('harga') : '' }}" min="0" step="0.01" required></label>
<label>Deskripsi<textarea name="deskripsi">{{ old('_form') === 'create' ? old('deskripsi') : '' }}</textarea></label>
</div>
<button type="submit">Simpan</button>
</form>
</section>
@endsection
