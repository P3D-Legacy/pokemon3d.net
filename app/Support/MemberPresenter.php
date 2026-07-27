<?php

namespace App\Support;

use App\Models\User;

class MemberPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function card(User $user): array
    {
        $settings = $user->settings();

        return [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $settings->get('name') ? $user->name : null,
            'profile_photo_url' => $user->profile_photo_url,
            'location' => $user->location,
            'joined' => $user->created_at?->isoFormat('LL'),
            'joined_for_humans' => $user->created_at?->diffForHumans(),
            'last_online' => $user->last_active_at
                ? (now()->subDay()->greaterThan($user->last_active_at)
                    ? $user->last_active_at->isoFormat('LL')
                    : $user->last_active_at->diffForHumans())
                : null,
            'has_game_save' => $user->gamesave !== null,
            'has_gamejolt' => $user->gamejolt !== null,
            'url' => route('member.show', $user->username),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function show(User $user): array
    {
        $settings = $user->settings();

        return [
            'id' => $user->id,
            'username' => $user->username,
            'profile_photo_url' => $user->profile_photo_url,
            'cover_image' => EmblemCatalogue::coverImageUrl($user),
            'about' => [
                'name' => $settings->get('name') ? $user->name : null,
                'joined' => $user->created_at?->isoFormat('LL'),
                'joined_for_humans' => $user->created_at?->diffForHumans(),
                'last_online' => $user->last_active_at
                    ? (now()->subDay()->greaterThan($user->last_active_at)
                        ? $user->last_active_at->isoFormat('LL')
                        : $user->last_active_at->diffForHumans())
                    : null,
                'birthday' => ($user->birthdate && ($settings->get('birthdate') || $settings->get('age')))
                    ? [
                        'date' => $settings->get('birthdate') ? $user->birthdate->isoFormat('LL') : null,
                        'age' => $settings->get('age') ? $user->birthdate->age.' '.__('years old') : null,
                    ]
                    : null,
                'location' => $user->location,
                'gender' => match ((int) $user->gender) {
                    1 => __('Male'),
                    2 => __('Female'),
                    3 => __('Genderless'),
                    default => $user->gender ? __('Unknown') : null,
                },
                'about' => $user->about,
            ],
            'accounts' => [
                'gamejolt' => $user->gamejolt
                    ? [
                        'username' => $user->gamejolt->username,
                        'url' => 'https://gamejolt.com/@'.$user->gamejolt->username,
                    ]
                    : null,
                'discord' => $user->discord
                    ? [
                        'label' => $user->discord->username.'#'.$user->discord->discriminator,
                        'url' => 'https://discord.com/users/'.$user->discord->id,
                    ]
                    : null,
                'twitch' => $user->twitch
                    ? [
                        'username' => $user->twitch->username,
                        'url' => 'https://twitch.tv/'.$user->twitch->username,
                    ]
                    : null,
            ],
            'gameSave' => GameSavePresenter::forUser($user),
        ];
    }
}
