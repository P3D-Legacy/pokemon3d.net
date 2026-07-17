<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Resource $resource): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Resource $resource): bool
    {
        return (int) $resource->user_id === (int) $user->id;
    }

    public function delete(User $user, Resource $resource): bool
    {
        return (int) $resource->user_id === (int) $user->id;
    }

    public function postUpdate(User $user, Resource $resource): bool
    {
        return (int) $resource->user_id === (int) $user->id;
    }

    public function rate(User $user, Resource $resource): bool
    {
        return (int) $resource->user_id !== (int) $user->id;
    }

    public function like(User $user, Resource $resource): bool
    {
        return (int) $resource->user_id !== (int) $user->id || (bool) config('app.debug');
    }
}
