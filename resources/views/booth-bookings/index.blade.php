@extends('layouts.event-management')

@section('event-content')
<div class="mb-5">
    <h2 class="font-display text-lg font-bold text-ink">Pemesanan booth</h2>
    <p class="text-sm text-muted">Pengajuan dari vendor untuk booth di event ini.</p>
</div>

<div class="card overflow-hidden">
    @if($boothBookings->isEmpty())
        <x-empty icon="users" title="Belum ada pemesanan booth" text="Pengajuan vendor akan tampil di sini." />
    @else
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Booth</th><th>Vendor</th><th>Kontak</th><th>Status</th><th>Diajukan</th></tr></thead>
                <tbody>
                @foreach($boothBookings as $b)
                    <tr>
                        <td class="font-display font-bold text-ink">{{ $b->booth->nama_booth ?? '—' }}</td>
                        <td class="font-semibold text-ink">{{ $b->nama_brand ?? $b->user->name ?? '—' }}</td>
                        <td class="text-muted">{{ $b->user->email ?? '—' }}</td>
                        <td><x-badge :status="$b->status ?? null" /></td>
                        <td class="whitespace-nowrap text-xs text-muted"><x-date :value="$b->created_at ?? null" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pager :items="$boothBookings" />
    @endif
</div>
@endsection
