<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventPublicController extends Controller
{
    public function show(Request $request): View
    {
        $event = $this->resolveEvent($request);

        $event->load(['ticketTypes', 'booths', 'rundowns']);

        return view('events.public.show', compact('event'));
    }

    public function tickets(Request $request): View|JsonResponse
    {
        $event = $this->resolveEvent($request);
        $ticketTypes = $event->ticketTypes()->orderBy('harga')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'event' => $event->nama_event,
                'ticket_types' => $ticketTypes,
            ]);
        }

        return view('events.public.tickets', compact('event', 'ticketTypes'));
    }
}
