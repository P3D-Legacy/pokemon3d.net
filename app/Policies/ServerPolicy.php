<?php

namespace App\Policies;

use App\Models\Server;
use App\Models\User;

class ServerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Server $server): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $user->loadMissing(['gamejolt', 'gamesave']);

        return $user->gamejolt !== null && $user->gamesave !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Server $server): bool
    {
        return (int) $server->user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Server $server): bool
    {
        return (int) $server->user_id === (int) $user->id;
    }

    /**
     * Determine whether the user can reactivate the model.
     */
    public function reactivate(User $user, Server $server): bool
    {
        return (int) $server->user_id === (int) $user->id;
    }
}
