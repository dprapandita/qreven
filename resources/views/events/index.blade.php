<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Event Saya - {{ config('app.name', 'Laravel') }}</title>
    </head>
    <body>
        <h1>Event Saya</h1>

        @forelse ($events as $event)
            <article>
                <h2><a href="{{ route('events.show', $event) }}">{{ $event->nama_event }}</a></h2>
                <p>{{ $event->lokasi }} &middot; {{ $event->tanggal_mulai }}</p>
                <p>{{ $event->subdomain }}.{{ config('events.base_domain') }}</p>
                <p>{{ $event->deskripsi }}</p>
            </article>
        @empty
            <p>Belum ada event.</p>
        @endforelse
    </body>
</html>
