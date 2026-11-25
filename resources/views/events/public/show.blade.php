<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $event->nama_event }}</title>
    </head>
    <body>
        <h1>{{ $event->nama_event }}</h1>
        <p>{{ $event->lokasi }}</p>
        <p>{{ $event->tanggal_mulai }} &ndash; {{ $event->tanggal_selesai }} ({{ $event->timezone }})</p>
        <p>{{ $event->deskripsi }}</p>

        <h2>Tiket</h2>
        @forelse ($event->ticketTypes as $ticketType)
            <article>
                <h3>{{ $ticketType->nama_tiket }}</h3>
                <p>Harga: {{ $ticketType->harga }}</p>
                <p>Kuota tersedia: {{ $ticketType->kuota }}</p>
            </article>
        @empty
            <p>Belum ada jenis tiket.</p>
        @endforelse

        <h2>Booth</h2>
        @forelse ($event->booths as $booth)
            <article>
                <h3>{{ $booth->nama_booth }}</h3>
                <p>Harga: {{ $booth->harga }}</p>
                <p>Status: {{ $booth->status->value }}</p>
            </article>
        @empty
            <p>Belum ada booth.</p>
        @endforelse

        <h2>Rundown</h2>
        @forelse ($event->rundowns as $rundown)
            <article>
                <h3>{{ $rundown->nama_kegiatan }}</h3>
                <p>{{ $rundown->waktu_mulai }} &ndash; {{ $rundown->waktu_selesai }}</p>
            </article>
        @empty
            <p>Belum ada rundown.</p>
        @endforelse
    </body>
</html>
