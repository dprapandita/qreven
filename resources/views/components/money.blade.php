@props(['value' => 0])
@if((float) $value <= 0) Gratis @else Rp {{ number_format((float) $value, 0, ',', '.') }} @endif
