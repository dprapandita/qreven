<?php

namespace App\Policies;

use App\Models\BoothBooking;
use App\Models\User;

class BoothBookingPolicy
{
    public function view(User $user, BoothBooking $boothBooking): bool
    {
        return $boothBooking->user_id === $user->id
            || $user->can('viewOrders', $boothBooking->booth->event);
    }

    public function update(User $user, BoothBooking $boothBooking): bool
    {
        return $user->can('update', $boothBooking->booth->event);
    }

    public function cancel(User $user, BoothBooking $boothBooking): bool
    {
        return $boothBooking->user_id === $user->id
            || $user->can('manageOrders', $boothBooking->booth->event);
    }
}
