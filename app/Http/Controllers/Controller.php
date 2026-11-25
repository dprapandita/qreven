<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        abort_unless($user->can('view', $event), 404);
    }

    protected function authorizeEventAccess(Event $event, string $ability): void
    {
        $this->ensureCanView($event);

        Gate::authorize($ability, $event);
    }
}
