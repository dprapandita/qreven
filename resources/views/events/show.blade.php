<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $event->nama_event }} - {{ config('app.name', 'Laravel') }}</title>
    </head>
    <body>
        <h1>{{ $event->nama_event }}</h1>
        <p>{{ $event->lokasi }}</p>
        <p>{{ $event->tanggal_mulai }} &ndash; {{ $event->tanggal_selesai }} ({{ $event->timezone }})</p>
        <p>{{ $event->deskripsi }}</p>
        <p>Subdomain: {{ $event->subdomain }}.{{ config('events.base_domain') }}</p>
        <p>Status: {{ $event->status->value }}</p>
    </body>
</html>
