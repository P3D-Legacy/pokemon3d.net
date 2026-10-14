import { Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, BookOpenIcon, ClockCounterClockwiseIcon, PencilSimpleIcon } from '@phosphor-icons/react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

type Props = {
    entry: {
        title: string;
        slug: string;
        game_id: string;
        type_1: string | null;
        type_2: string | null;
        hp: number | null;
        attack: number | null;
        defense: number | null;
        special_attack: number | null;
        special_defense: number | null;
        speed: number | null;
        ability_1: string | null;
        ability_2: string | null;
        hidden_ability: string | null;
        height: number | null;
        weight: number | null;
        species: string | null;
        pokedex_entry: string | null;
        moves: Array<{ level?: string | number; move_id?: string }>;
        egg_moves: string[];
        machines: string[];
        evolution: { evolves_to?: string | null; method?: string | null; value?: string | null } | null;
        notes_html: string;
        permissions: { can_update: boolean };
    };
    copy: {
        wiki: string;
        edit: string;
        history: string;
        notes: string;
        stats: string;
        abilities: string;
        moves: string;
    };
};

const stats = [
    ['HP', 'hp'],
    ['Attack', 'attack'],
    ['Defense', 'defense'],
    ['Sp. Atk', 'special_attack'],
    ['Sp. Def', 'special_defense'],
    ['Speed', 'speed'],
] as const;

export default function WikiPokemon({ entry, copy }: Props) {
    return (
        <>
            <Head title={entry.title} />

            <div className="mx-auto flex w-full max-w-4xl flex-col gap-6 px-4 py-10 sm:px-6 lg:px-8">
                <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                    <Link href="/wiki">
                        <ArrowLeftIcon data-icon="inline-start" />
                        {copy.wiki}
                    </Link>
                </Button>

                <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <BookOpenIcon className="size-5" weight="fill" />
                            <span className="text-sm">#{entry.game_id}</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{entry.title}</h1>
                        <div className="flex flex-wrap gap-2">
                            {entry.type_1 ? <Badge>{entry.type_1}</Badge> : null}
                            {entry.type_2 ? <Badge variant="secondary">{entry.type_2}</Badge> : null}
                            {entry.species ? <Badge variant="outline">{entry.species}</Badge> : null}
                        </div>
                        {entry.pokedex_entry ? (
                            <p className="max-w-2xl text-sm text-muted-foreground">{entry.pokedex_entry}</p>
                        ) : null}
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <Button variant="outline" size="sm" asChild>
                            <Link href={`/wiki/${entry.slug}/history`}>
                                <ClockCounterClockwiseIcon data-icon="inline-start" />
                                {copy.history}
                            </Link>
                        </Button>
                        {entry.permissions.can_update ? (
                            <Button size="sm" asChild>
                                <Link href={`/wiki/${entry.slug}/edit`}>
                                    <PencilSimpleIcon data-icon="inline-start" />
                                    {copy.edit}
                                </Link>
                            </Button>
                        ) : null}
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">{copy.stats}</CardTitle>
                        </CardHeader>
                        <CardContent className="flex flex-col gap-2 text-sm">
                            {stats.map(([label, key]) => (
                                <div key={key} className="flex items-center justify-between gap-4">
                                    <span>{label}</span>
                                    <span className="font-medium">{entry[key] ?? 'n/a'}</span>
                                </div>
                            ))}
                            <div className="flex justify-between gap-4">
                                <span>Height</span>
                                <span>{entry.height ?? 'n/a'}</span>
                            </div>
                            <div className="flex justify-between gap-4">
                                <span>Weight</span>
                                <span>{entry.weight ?? 'n/a'}</span>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">{copy.abilities}</CardTitle>
                        </CardHeader>
                        <CardContent className="flex flex-col gap-2 text-sm">
                            <p>Ability 1: {entry.ability_1 ?? 'n/a'}</p>
                            <p>Ability 2: {entry.ability_2 ?? 'n/a'}</p>
                            <p>Hidden: {entry.hidden_ability ?? 'n/a'}</p>
                            {entry.evolution?.evolves_to ? (
                                <p>
                                    Evolves to {entry.evolution.evolves_to}
                                    {entry.evolution.method ? ` (${entry.evolution.method} ${entry.evolution.value ?? ''})` : ''}
                                </p>
                            ) : null}
                        </CardContent>
                    </Card>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base">{copy.moves}</CardTitle>
                    </CardHeader>
                    <CardContent className="flex flex-col gap-3 text-sm">
                        <div className="flex flex-col gap-1">
                            {entry.moves.map((move, index) => (
                                <p key={`${move.move_id}-${index}`}>
                                    Lv. {move.level ?? '?'} · Move {move.move_id}
                                </p>
                            ))}
                        </div>
                        {entry.egg_moves.length > 0 ? <p>Egg: {entry.egg_moves.join(', ')}</p> : null}
                        {entry.machines.length > 0 ? <p>TM/HM: {entry.machines.join(', ')}</p> : null}
                    </CardContent>
                </Card>

                {entry.notes_html ? (
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">{copy.notes}</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <article
                                className="prose dark:prose-invert max-w-none"
                                dangerouslySetInnerHTML={{ __html: entry.notes_html }}
                            />
                        </CardContent>
                    </Card>
                ) : null}
            </div>
        </>
    );
}
