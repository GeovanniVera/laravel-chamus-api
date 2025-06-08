<?php

namespace App\Policies;

use App\Models\Museum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MuseumPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Museum $museum): bool
    {
        return $user->id === $museum->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Museum $museum): bool
    {
        return $user->id === $museum->user_id;
    }
}
