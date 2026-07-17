<?php

namespace App\Support;

use App\Helpers\DiscordHelper;
use App\Helpers\NumberHelper;
use App\Helpers\StatsHelper;
use App\Helpers\XenForoHelper;
use App\Models\GameVersion;
use App\Models\User;
use Digikraaft\ReviewRating\Models\Review;
use Illuminate\Support\Facades\Cache;

class HomeStats
{
    /**
     * @return list<array{key: string, label: string, value: string, hint: ?string}>
     */
    public static function all(): array
    {
        return Cache::remember('home.stats', now()->addMinutes(5), function (): array {
            $reviews = Review::query()
                ->where('model_type', GameVersion::class)
                ->get(['rating']);

            return [
                [
                    'key' => 'reviews',
                    'label' => __('Reviews'),
                    'value' => (string) $reviews->count(),
                    'hint' => $reviews->isNotEmpty() ? (string) round($reviews->avg('rating'), 1).'/5' : null,
                ],
                [
                    'key' => 'season',
                    'label' => __('Season'),
                    'value' => ucfirst(StatsHelper::getInGameSeason()),
                    'hint' => null,
                ],
                [
                    'key' => 'players',
                    'label' => __('Online'),
                    'value' => (string) NumberHelper::nearestK(StatsHelper::countPlayers()),
                    'hint' => null,
                ],
                [
                    'key' => 'users',
                    'label' => __('Users'),
                    'value' => (string) NumberHelper::nearestK(User::query()->count()),
                    'hint' => null,
                ],
                [
                    'key' => 'discord',
                    'label' => __('Discord'),
                    'value' => (string) NumberHelper::nearestK(DiscordHelper::countMembers()),
                    'hint' => null,
                ],
                [
                    'key' => 'forum',
                    'label' => __('Forum'),
                    'value' => (string) NumberHelper::nearestK(XenForoHelper::getUserCount()),
                    'hint' => null,
                ],
            ];
        });
    }
}
