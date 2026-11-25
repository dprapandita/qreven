<?php

namespace App\Policies;

use App\Models\Rundown;
use App\Models\User;

class RundownPolicy
{
    public function view(User $user, Rundown $rundown): bool
    {
        return $user->can('view', $rundown->event);
    }

    public function update(User $user, Rundown $rundown): bool
    {
        return $user->can('update', $rundown->event);
    }

    public function delete(User $user, Rundown $rundown): bool
    {
        return $user->can('update', $rundown->event);
    }
}
