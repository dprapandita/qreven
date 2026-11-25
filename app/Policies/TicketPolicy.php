<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        return $ticket->ticketOrder->user_id === $user->id
            || $user->can('viewOrders', $ticket->ticketOrder->event);
    }

    public function checkIn(User $user, Ticket $ticket): bool
    {
        return $user->can('checkIn', $ticket->ticketOrder->event);
    }
}
