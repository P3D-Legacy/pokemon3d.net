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
        type: string | null;
        category: string | null;
        power: number | null;
        accuracy: number | null;
        pp: number | null;
        effect: string | null;
        notes_html: string;
        permissions: { can_update: boolean };
    };
    copy: {
        wiki: string;
        edit: string;
        history: string;
        notes: string;
        effect: string;
        type: string;
        category: string;
        power: string;
        accuracy: string;
        pp: string;
    };
};

export default function WikiMove({ entry, copy }: Props) {
    return (
        <>
            <Head title={entry.title} />

            <div className="mx-auto flex w-full max-w-3xl flex-col gap-6 px-4 py-10 sm:px-6 lg:px-8">
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
                            {entry.type ? <Badge>{entry.type}</Badge> : null}
                            {entry.category ? <Badge variant="secondary">{entry.category}</Badge> : null}
                        </div>
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
                <Card>
                    <CardContent className="grid grid-cols-2 gap-3 pt-(--card-spacing) text-sm sm:grid-cols-4">
                        <p>{copy.power}: {entry.power ?? 'n/a'}</p>
                        <p>{copy.accuracy}: {entry.accuracy ?? 'n/a'}</p>
                        <p>{copy.pp}: {entry.pp ?? 'n/a'}</p>
                        <p>{copy.type}: {entry.type ?? 'n/a'}</p>
                    </CardContent>
                </Card>
                {entry.effect ? (
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">{copy.effect}</CardTitle>
                        </CardHeader>
                        <CardContent className="text-sm">{entry.effect}</CardContent>
                    </Card>
                ) : null}
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
