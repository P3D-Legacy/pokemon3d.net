<?php

namespace App\Actions\Auth;

use App\Helpers\XenForoHelper;
use App\Models\ForumAccount;
use App\Models\User;

class AuthenticateForumUser
{
    /**
     * Authenticate a user via forum credentials.
     *
     * @return User|string User on success, or an error message string.
     */
    public function __invoke(string $login, string $password): User|string
    {
        $auth = XenForoHelper::postAuth($login, $password);

        if (isset($auth['error'])) {
            return $auth['message'] ?? 'Authentication failed.';
        }

        $forumUsername = $auth['user']['username'] ?? $login;

        $forumAccount = ForumAccount::query()->where('username', $forumUsername)->first();

        if (! $forumAccount) {
            return 'This Forum Account is not associated with a P3D account yet.';
        }

        $user = $forumAccount->user()->first();

        if (! $user) {
            return 'Could not find the user associated with this Forum Account.';
        }

        $forumAccount->touchVerify();

        return $user;
    }
}
