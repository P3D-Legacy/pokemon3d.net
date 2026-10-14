import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, BookOpenIcon } from '@phosphor-icons/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

type Entry = {
    kind: string;
    title: string;
    slug: string;
    notes?: string | null;
    effect?: string | null;
    type_1?: string | null;
    type_2?: string | null;
    type?: string | null;
    category?: string | null;
    hp?: number | null;
    attack?: number | null;
    defense?: number | null;
    special_attack?: number | null;
    special_defense?: number | null;
    speed?: number | null;
    height?: number | null;
    weight?: number | null;
    species?: string | null;
    pokedex_entry?: string | null;
    power?: number | null;
    accuracy?: number | null;
    pp?: number | null;
    buy_price?: number | null;
    sell_price?: number | null;
};

type Props = {
    entry: Entry;
    copy: {
        title: string;
        wiki: string;
        notes: string;
        summary: string;
        submit: string;
        cancel: string;
        markdownHelp: string;
    };
};

export default function WikiEditEncyclopedia({ entry, copy }: Props) {
    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={`/wiki/${entry.slug}`}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            {entry.title}
                        </Link>
                    </Button>
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <BookOpenIcon className="size-5" weight="fill" />
                        <span className="text-sm">{copy.wiki}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                    <p className="text-sm text-muted-foreground">{copy.markdownHelp}</p>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">{entry.title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Form action={`/wiki/${entry.slug}`} method="put" className="flex flex-col gap-4">
                            {({ processing, errors }) => (
                                <>
                                    {entry.kind === 'pokemon' ? (
                                        <>
                                            <Field label="Type 1" name="type_1" defaultValue={entry.type_1} error={errors.type_1} />
                                            <Field label="Type 2" name="type_2" defaultValue={entry.type_2} error={errors.type_2} />
                                            <Field label="Species" name="species" defaultValue={entry.species} error={errors.species} />
                                            <div className="flex flex-col gap-2">
                                                <Label htmlFor="pokedex_entry">Pokédex</Label>
                                                <Textarea
                                                    id="pokedex_entry"
                                                    name="pokedex_entry"
                                                    defaultValue={entry.pokedex_entry ?? ''}
                                                />
                                            </div>
                                        </>
                                    ) : null}

                                    {entry.kind === 'move' ? (
                                        <>
                                            <Field label="Type" name="type" defaultValue={entry.type} error={errors.type} />
                                            <Field label="Category" name="category" defaultValue={entry.category} error={errors.category} />
                                            <Field label="Power" name="power" defaultValue={entry.power} error={errors.power} />
                                            <Field label="Accuracy" name="accuracy" defaultValue={entry.accuracy} error={errors.accuracy} />
                                            <Field label="PP" name="pp" defaultValue={entry.pp} error={errors.pp} />
                                        </>
                                    ) : null}

                                    {entry.kind === 'item' ? (
                                        <>
                                            <Field label="Category" name="category" defaultValue={entry.category} error={errors.category} />
                                            <Field label="Buy" name="buy_price" defaultValue={entry.buy_price} error={errors.buy_price} />
                                            <Field label="Sell" name="sell_price" defaultValue={entry.sell_price} error={errors.sell_price} />
                                        </>
                                    ) : null}

                                    {entry.kind !== 'pokemon' ? (
                                        <div className="flex flex-col gap-2">
                                            <Label htmlFor="effect">Effect</Label>
                                            <Textarea id="effect" name="effect" defaultValue={entry.effect ?? ''} />
                                            <InputError message={errors.effect} />
                                        </div>
                                    ) : null}

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="notes">{copy.notes}</Label>
                                        <Textarea id="notes" name="notes" className="min-h-40" defaultValue={entry.notes ?? ''} />
                                        <InputError message={errors.notes} />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="summary">{copy.summary}</Label>
                                        <Input id="summary" name="summary" />
                                    </div>
                                    <div className="flex flex-wrap justify-end gap-2">
                                        <Button type="button" variant="outline" asChild>
                                            <Link href={`/wiki/${entry.slug}`}>{copy.cancel}</Link>
                                        </Button>
                                        <Button type="submit" disabled={processing}>
                                            {copy.submit}
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

function Field({
    label,
    name,
    defaultValue,
    error,
}: {
    label: string;
    name: string;
    defaultValue?: string | number | null;
    error?: string;
}) {
    return (
        <div className="flex flex-col gap-2">
            <Label htmlFor={name}>{label}</Label>
            <Input id={name} name={name} defaultValue={defaultValue ?? ''} />
            <InputError message={error} />
        </div>
    );
}
