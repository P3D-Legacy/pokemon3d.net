<?php

namespace App\Actions\Jetstream;

use App\Stats\UserRegistrationStats;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     */
    public function delete($user): void
    {
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
        UserRegistrationStats::decrease();
    }
}
