<?php

namespace App\Policies;

use App\Models\TicketOrder;
use App\Models\User;

class TicketOrderPolicy
{
    public function view(User $user, TicketOrder $ticketOrder): bool
    {
        return $ticketOrder->user_id === $user->id
            || $user->can('viewOrders', $ticketOrder->event);
    }

    public function markPaid(User $user, TicketOrder $ticketOrder): bool
    {
        return $user->can('manageOrders', $ticketOrder->event);
    }

    public function cancel(User $user, TicketOrder $ticketOrder): bool
    {
        return $ticketOrder->user_id === $user->id
            || $user->can('manageOrders', $ticketOrder->event);
    }
}
