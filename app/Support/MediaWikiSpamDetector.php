<?php

namespace App\Support;

use Illuminate\Support\Str;

class MediaWikiSpamDetector
{
    /**
     * @var list<string>
     */
    private const COMMERCIAL_PHRASES = [
        'keto',
        'payday',
        'casino',
        'viagra',
        'cialis',
        'forex',
        'baccarat',
        'blackjack',
        'weight loss',
        'home loan',
        'payday loan',
        'massage',
        'assembly jobs',
        'slot machine',
        'online slots',
        'online casino',
        'gold reviews',
        'goldco',
        'diet pills',
        'cbd oil',
        'cannabis',
        'insurance quote',
        'credit score',
        'escorts',
        'dating site',
        'enlargement',
        'faxless',
        'ira plan',
        'irs-authorized',
        'real money online',
        'online gambling',
    ];

    /**
     * @var list<string>
     */
    private const GAME_PHRASES = [
        'pokemon',
        'pokémon',
        'p3d',
        'pokemon3d',
        'pokémon 3d',
        'johto',
        'kanto',
        'hoenn',
        'sinnoh',
        'unova',
        'kalos',
        'alola',
        'galar',
        'paldea',
        'pokedex',
        'pokédex',
        'gym leader',
        'team rocket',
        'poké ball',
        'pokeball',
        'poke ball',
        'professor oak',
        'professor elm',
        'mega evol',
        'national dex',
        'game jolt',
    ];

    /**
     * @param  list<string>  $blockedUsers
     */
    public function __construct(private array $blockedUsers = [])
    {
        $this->blockedUsers = array_values(array_unique(array_map(
            fn (string $user): string => Str::lower($user),
            $blockedUsers
        )));
    }

    public function isSpam(string $title, string $wikitext, ?string $username = null): bool
    {
        return $this->reason($title, $wikitext, $username) !== null;
    }

    public function reason(string $title, string $wikitext, ?string $username = null): ?string
    {
        if ($username && $this->isBlockedUser($username)) {
            return "blocked user {$username}";
        }

        $haystack = $this->normalize("{$title}\n{$wikitext}");

        if ($username && $this->hasCommercialSpam($this->normalize($username))) {
            return "spam username {$username}";
        }

        if ($this->hasCommercialSpam($this->normalize($title))) {
            return 'commercial spam title';
        }

        if ($this->hasCommercialSpam($haystack) && ! $this->looksLikeGameContent($title, $wikitext)) {
            return 'commercial spam';
        }

        if ($username && $this->looksLikeSpamUsername($username) && ! $this->looksLikeGameContent($title, $wikitext)) {
            return "spam username {$username}";
        }

        if ($this->isMostlyNonLatin($haystack) && ! $this->looksLikeGameContent($title, $wikitext)) {
            return 'non-English commercial spam';
        }

        return null;
    }

    public function looksLikeGameContent(string $title, string $body): bool
    {
        return $this->containsPhrase($this->normalize("{$title}\n{$body}"), self::GAME_PHRASES);
    }

    public function isBlockedUser(string $username): bool
    {
        return in_array(Str::lower($username), $this->blockedUsers, true);
    }

    private function hasCommercialSpam(string $haystack): bool
    {
        return $this->containsPhrase($haystack, self::COMMERCIAL_PHRASES);
    }

    private function looksLikeSpamUsername(string $username): bool
    {
        return (bool) preg_match('/^[A-Z][a-z]{2,}[A-Z][a-z]{2,}\d+$/', $username);
    }

    private function isMostlyNonLatin(string $text): bool
    {
        $letters = preg_match_all('/\p{L}/u', $text) ?: 0;
        $latin = preg_match_all('/\p{Latin}/u', $text) ?: 0;

        if ($letters < 40) {
            return false;
        }

        return ($latin / $letters) < 0.4;
    }

    /**
     * @param  list<string>  $phrases
     */
    private function containsPhrase(string $haystack, array $phrases): bool
    {
        return Str::contains($haystack, $phrases);
    }

    private function normalize(string $text): string
    {
        return Str::of($text)
            ->replaceMatches('/<br\s*\/?>/i', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->lower()
            ->trim()
            ->toString();
    }
}
