<?php

namespace App\Policies;

use App\Models\Booth;
use App\Models\User;

class BoothPolicy
{
    public function view(User $user, Booth $booth): bool
    {
        return $user->can('view', $booth->event);
    }

    public function update(User $user, Booth $booth): bool
    {
        return $user->can('update', $booth->event);
    }

    public function delete(User $user, Booth $booth): bool
    {
        return $user->can('update', $booth->event);
    }
}
