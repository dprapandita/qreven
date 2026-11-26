<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function resolveEvent(Request $request): Event
    {
        $event = $request->attributes->get('event');

        abort_unless($event instanceof Event, 404);

        return $event;
    }

    protected function ensureCanView(Event $event): void
    {
        $user = auth()->user();

        abort_if($user === null, 401);
        abort_unless($event->isAccessibleBy($user), 404);
    }

    protected function authorizeEventAccess(Event $event, string $ability): void
    {
        $user = auth()->user();

        abort_if($user === null, 401);
        abort_unless($event->isAccessibleBy($user), 404);

        $allowed = match ($ability) {
            'view' => true,
            'delete' => $event->isOwnedBy($user),
            'manage' => $event->canBeManagedBy($user),
            'orders' => $event->canManageOrdersBy($user),
            'checkin' => $event->canCheckInBy($user),
            'members' => $event->canManageMembersBy($user),
            default => false,
        };

        abort_unless($allowed, 403);
    }
}
