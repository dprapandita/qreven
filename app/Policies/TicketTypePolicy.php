<?php

namespace App\Policies;

use App\Models\TicketType;
use App\Models\User;

class TicketTypePolicy
{
    public function view(User $user, TicketType $ticketType): bool
    {
        return $user->can('view', $ticketType->event);
    }

    public function update(User $user, TicketType $ticketType): bool
    {
        return $user->can('update', $ticketType->event);
    }

    public function delete(User $user, TicketType $ticketType): bool
    {
        return $user->can('update', $ticketType->event);
    }
}
