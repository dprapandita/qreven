@extends('layouts.event-management')

@section('event-content')
<div class="mb-5">
    <h2 class="text-lg font-extrabold text-brand-950">Pemesanan booth</h2>
    <p class="text-sm text-slate-500">Pengajuan dari vendor untuk booth di event ini.</p>
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
                        <td class="font-bold text-brand-950">{{ $b->booth->name ?? '—' }}</td>
                        <td class="font-semibold">{{ $b->vendor_name ?? $b->name ?? '—' }}</td>
                        <td class="text-slate-500">{{ $b->vendor_email ?? $b->email ?? $b->phone ?? '—' }}</td>
                        <td><x-badge :status="$b->status ?? null" /></td>
                        <td class="whitespace-nowrap text-slate-500"><x-date :value="$b->created_at ?? null" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <x-pager :items="$boothBookings" />
    @endif
</div>
@endsection
