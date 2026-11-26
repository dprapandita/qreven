<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $organizer = $request->user();
        $events = $organizer->events();
        $ownedEventIds = (clone $events)->select('events.id');

        return view('dashboard', [
            'organizer' => $organizer,
            'eventCount' => (clone $events)->count(),
            'pendingOrderCount' => TicketOrder::query()
                ->whereIn('event_id', $ownedEventIds)
                ->where('status_pembayaran', 'pending')
                ->count(),
            'boothCount' => Booth::query()->whereIn('event_id', $ownedEventIds)->count(),
            'recentEvents' => $events->latest('events.created_at')->limit(5)->get(),
        ]);
    }
}
