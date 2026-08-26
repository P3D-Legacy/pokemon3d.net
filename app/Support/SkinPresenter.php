<?php

namespace App\Support;

use App\Models\Skin;
use App\Models\User;

class SkinPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function card(Skin $skin, ?User $viewer = null): array
    {
        $viewerGamejoltId = $viewer?->gamejolt?->id;
        $timezone = $viewer?->timezone ?? config('app.timezone');

        return [
            'uuid' => $skin->uuid,
            'name' => $skin->name,
            'public' => (bool) $skin->public,
            'owner_id' => $skin->owner_id,
            'image_url' => SkinStorage::urlLibrary(
                $skin->uuid,
                $skin->updated_at?->timestamp ?? now()->timestamp
            ),
            'file_size' => 'N/A',
            'likes_count' => (int) ($skin->likers_count ?? $skin->likers()->count()),
            'liked' => (bool) ($skin->has_liked ?? ($viewer ? $skin->isLikedBy($viewer) : false)),
            'is_owner' => $viewerGamejoltId !== null && (int) $viewerGamejoltId === (int) $skin->owner_id,
            'uploaded_at' => now()->subMonth() > $skin->created_at
                ? $skin->created_at->setTimezone($timezone)->isoFormat('LLL')
                : $skin->created_at->diffForHumans(),
            'publisher' => $skin->user
                ? [
                    'username' => $skin->user->username,
                    'url' => route('member.show', $skin->user),
                ]
                : null,
            'show_url' => $skin->public ? route('skin-show', $skin->uuid) : null,
        ];
    }
}
