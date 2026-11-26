@props(['value' => null, 'format' => 'd M Y, H:i'])
@php
    try { $out = $value ? \Illuminate\Support\Carbon::parse($value)->translatedFormat($format) : '—'; }
    catch (\Throwable $e) { $out = (string) $value; }
@endphp
{{ $out }}
