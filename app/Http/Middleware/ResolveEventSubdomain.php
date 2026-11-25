<?php

namespace App\Http\Middleware;

use App\Enums\EventStatus;
use App\Models\Event;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ResolveEventSubdomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = $request->route('event_subdomain');

        if (! is_string($subdomain) || $subdomain === '') {
            abort(404);
        }

        if (in_array($subdomain, config('events.reserved_subdomains'), true)) {
            abort(404);
        }

        $event = Event::query()->where('subdomain', $subdomain)->first();

        if ($event === null) {
            abort(404);
        }

        if ($event->status !== EventStatus::Published) {
            $user = $request->user();

            if ($user === null || Gate::forUser($user)->denies('view', $event)) {
                abort(404);
            }
        }

        $request->attributes->set('event', $event);

        return $next($request);
    }
}
