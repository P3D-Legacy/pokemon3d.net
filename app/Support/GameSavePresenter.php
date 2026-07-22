<?php

namespace App\Support;

use App\Models\Pokedex;
use App\Models\User;

class GameSavePresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function forUser(User $user): array
    {
        $user->loadMissing(['gamesave', 'gamejolt.trophies']);

        $gamesave = $user->gamesave;
        $gamejolt = $user->gamejolt;

        if (! $gamejolt || ! $gamesave) {
            return [
                'available' => false,
                'message' => __('User has not connected their Game Jolt account yet'),
            ];
        }

        $trophies = $gamejolt->trophies ?? collect();
        $pokedexes = Pokedex::query()
            ->orderBy('id')
            ->get()
            ->map(function (Pokedex $pokedex) use ($gamesave): array {
                $entries = $gamesave->getPokedexByIds($pokedex->pokemon_ids ?? []);
                $caughtCount = count(array_filter($entries, fn (array $entry): bool => $entry['caught']));
                $seenCount = count(array_filter($entries, fn (array $entry): bool => $entry['seen']));

                return [
                    'slug' => $pokedex->slug,
                    'name' => $pokedex->name,
                    'caught_count' => $caughtCount,
                    'seen_count' => $seenCount,
                    'entries' => $entries,
                ];
            })
            ->values()
            ->all();

        return [
            'available' => true,
            'last_synced' => $gamesave->updated_at?->diffForHumans(),
            'caught_count' => $gamesave->getCaughtPokemonCount(),
            'seen_count' => $gamesave->getSeenPokemonCount(),
            'party' => array_values($gamesave->getParty()),
            'details' => $gamesave->getPlayerDataDetails(),
            'pokedexes' => $pokedexes,
            'statistics' => array_values($gamesave->getStatistics()),
            'trophies' => [
                'achieved' => $trophies->where('achieved', true)->count(),
                'total' => $trophies->count(),
                'items' => $trophies->map(fn ($trophy): array => [
                    'id' => (int) $trophy->id,
                    'title' => $trophy->title ?? $trophy->name ?? 'Trophy',
                    'difficulty' => $trophy->difficulty ?? null,
                    'description' => $trophy->description ?? null,
                    'image_url' => $trophy->image_url ?? null,
                    'achieved' => (bool) ($trophy->achieved ?? false),
                ])->values()->all(),
            ],
        ];
    }
}
