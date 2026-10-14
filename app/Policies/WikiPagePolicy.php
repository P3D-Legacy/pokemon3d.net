<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WikiPage;

class WikiPagePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, WikiPage $wikiPage): bool
    {
        if ($wikiPage->published) {
            return true;
        }

        return $user?->can('wiki.show') ?? false;
    }

    public function create(User $user): bool
    {
        return $user->can('wiki.create');
    }

    public function update(User $user, WikiPage $wikiPage): bool
    {
        return $user->can('wiki.update');
    }

    public function delete(User $user, WikiPage $wikiPage): bool
    {
        return $user->can('wiki.destroy');
    }

    public function restore(User $user, WikiPage $wikiPage): bool
    {
        return $user->can('wiki.destroy');
    }

    public function forceDelete(User $user, WikiPage $wikiPage): bool
    {
        return $user->can('wiki.destroy');
    }

    public function viewHistory(?User $user): bool
    {
        return true;
    }

    public function editEncyclopedia(User $user): bool
    {
        return $user->can('wiki.update');
    }
}
