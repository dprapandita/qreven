@props(['status' => null])
@php
    $value = $status instanceof \BackedEnum ? $status->value : $status;
    $key = strtolower((string) $value);
    $color = match (true) {
        in_array($key, ['paid','lunas','confirmed','approved','published','active','checked_in','used','success']) => 'badge-green',
        in_array($key, ['pending','waiting','draft','unpaid','review']) => 'badge-amber',
        in_array($key, ['cancelled','canceled','rejected','expired','failed','refunded']) => 'badge-red',
        $key === '' => 'badge-gray',
        default => 'badge-accent',
    };
    $labels = [
        'draft' => 'Draft', 'published' => 'Terbit', 'pending' => 'Menunggu', 'paid' => 'Lunas', 'confirmed' => 'Dikonfirmasi',
        'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan', 'canceled' => 'Dibatalkan',
        'expired' => 'Kedaluwarsa', 'used' => 'Terpakai', 'checked_in' => 'Check-in', 'active' => 'Aktif', 'failed' => 'Gagal',
    ];
    $label = $labels[$key] ?? ($key === '' ? '—' : ucfirst(str_replace('_', ' ', $key)));
@endphp
<span {{ $attributes->merge(['class' => "badge $color"]) }}>{{ $slot->isEmpty() ? $label : $slot }}</span>
