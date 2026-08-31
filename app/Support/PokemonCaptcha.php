<?php

namespace App\Support;

class PokemonCaptcha
{
    public const SESSION_KEY = 'pokemon_captcha';

    public const QUESTION_COUNT = 3;

    /**
     * @return list<array{id: string, question: string, options: list<array{id: string, label: string}>}>
     */
    public static function issue(): array
    {
        $questions = collect(self::questions())
            ->shuffle()
            ->take(self::QUESTION_COUNT)
            ->values();

        $answers = [];
        $payload = [];

        foreach ($questions as $question) {
            $answers[$question['id']] = $question['answer'];

            $payload[] = [
                'id' => $question['id'],
                'question' => __($question['question']),
                'options' => collect($question['options'])
                    ->map(fn (string $label, string $id): array => [
                        'id' => $id,
                        'label' => __($label),
                    ])
                    ->shuffle()
                    ->values()
                    ->all(),
            ];
        }

        session([self::SESSION_KEY => $answers]);

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    public static function verify(array $answers): bool
    {
        $expected = session(self::SESSION_KEY);

        if (! is_array($expected) || count($expected) !== self::QUESTION_COUNT) {
            return false;
        }

        foreach ($expected as $questionId => $answerKey) {
            if (! is_string($questionId) || ! is_string($answerKey)) {
                return false;
            }

            $submitted = $answers[$questionId] ?? null;

            if (! is_string($submitted) || ! hash_equals($answerKey, $submitted)) {
                return false;
            }
        }

        return true;
    }

    public static function forget(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * @return array<string, array{id: string, question: string, answer: string, options: array<string, string>}>
     */
    public static function questions(): array
    {
        return [
            'pikachu_type' => [
                'id' => 'pikachu_type',
                'question' => 'What type is Pikachu?',
                'answer' => 'electric',
                'options' => [
                    'electric' => 'Electric',
                    'fire' => 'Fire',
                    'water' => 'Water',
                    'grass' => 'Grass',
                ],
            ],
            'kanto_fire_starter' => [
                'id' => 'kanto_fire_starter',
                'question' => 'Which of these is a Fire-type starter?',
                'answer' => 'charmander',
                'options' => [
                    'charmander' => 'Charmander',
                    'squirtle' => 'Squirtle',
                    'bulbasaur' => 'Bulbasaur',
                    'pikachu' => 'Pikachu',
                ],
            ],
            'poke_ball' => [
                'id' => 'poke_ball',
                'question' => 'What does a Poké Ball do?',
                'answer' => 'catch',
                'options' => [
                    'catch' => 'Catch Pokémon',
                    'heal' => 'Heal Pokémon',
                    'teach' => 'Teach moves',
                    'evolve' => 'Evolve Pokémon',
                ],
            ],
            'first_pokedex' => [
                'id' => 'first_pokedex',
                'question' => 'Who is the first Pokémon in the National Pokédex?',
                'answer' => 'bulbasaur',
                'options' => [
                    'bulbasaur' => 'Bulbasaur',
                    'pikachu' => 'Pikachu',
                    'mew' => 'Mew',
                    'charmander' => 'Charmander',
                ],
            ],
            'squirtle_type' => [
                'id' => 'squirtle_type',
                'question' => 'What type is Squirtle?',
                'answer' => 'water',
                'options' => [
                    'water' => 'Water',
                    'fire' => 'Fire',
                    'electric' => 'Electric',
                    'rock' => 'Rock',
                ],
            ],
            'legendary' => [
                'id' => 'legendary',
                'question' => 'Which of these is a Legendary Pokémon?',
                'answer' => 'mewtwo',
                'options' => [
                    'mewtwo' => 'Mewtwo',
                    'pidgey' => 'Pidgey',
                    'rattata' => 'Rattata',
                    'caterpie' => 'Caterpie',
                ],
            ],
            'pallet_town' => [
                'id' => 'pallet_town',
                'question' => 'What region is Pallet Town in?',
                'answer' => 'kanto',
                'options' => [
                    'kanto' => 'Kanto',
                    'johto' => 'Johto',
                    'hoenn' => 'Hoenn',
                    'sinnoh' => 'Sinnoh',
                ],
            ],
            'super_effective_fire' => [
                'id' => 'super_effective_fire',
                'question' => 'What type is Super Effective against Fire?',
                'answer' => 'water',
                'options' => [
                    'water' => 'Water',
                    'grass' => 'Grass',
                    'electric' => 'Electric',
                    'fire' => 'Fire',
                ],
            ],
            'charizard_prevo' => [
                'id' => 'charizard_prevo',
                'question' => 'Which Pokémon evolves into Charizard?',
                'answer' => 'charmeleon',
                'options' => [
                    'charmeleon' => 'Charmeleon',
                    'charmander' => 'Charmander',
                    'charizard' => 'Charizard',
                    'squirtle' => 'Squirtle',
                ],
            ],
            'ash_first' => [
                'id' => 'ash_first',
                'question' => "What was Ash's first Pokémon?",
                'answer' => 'pikachu',
                'options' => [
                    'pikachu' => 'Pikachu',
                    'charmander' => 'Charmander',
                    'squirtle' => 'Squirtle',
                    'bulbasaur' => 'Bulbasaur',
                ],
            ],
        ];
    }
}
