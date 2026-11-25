<?php

namespace App\Policies;

use App\Enums\EventRole;
use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Event $event): bool
    {
        return $event->isOwnedBy($user) || $event->hasMemberWithRole($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Event $event): bool
    {
        return $event->isOwnedBy($user)
            || $event->hasMemberWithRole($user, [EventRole::Administrator]);
    }

    public function delete(User $user, Event $event): bool
    {
        return $event->isOwnedBy($user);
    }

    public function manageMembers(User $user, Event $event): bool
    {
        return $event->isOwnedBy($user);
    }

    public function viewOrders(User $user, Event $event): bool
    {
        return $event->isOwnedBy($user)
            || $event->hasMemberWithRole($user, [EventRole::Administrator, EventRole::Finance]);
    }

    public function manageOrders(User $user, Event $event): bool
    {
        return $event->isOwnedBy($user)
            || $event->hasMemberWithRole($user, [EventRole::Finance]);
    }

    public function checkIn(User $user, Event $event): bool
    {
        return $event->isOwnedBy($user)
            || $event->hasMemberWithRole($user, [EventRole::Administrator, EventRole::CheckIn]);
    }
}
