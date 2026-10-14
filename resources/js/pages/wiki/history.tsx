import { Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, ClockCounterClockwiseIcon } from '@phosphor-icons/react';

import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';

type Revision = {
    id: number;
    title: string;
    summary: string | null;
    created_at: string | null;
    created_for_humans: string | null;
    author: string | null;
};

type Props = {
    entry: {
        title: string;
        slug: string;
    };
    revisions: Revision[];
    copy: {
        wiki: string;
        history: string;
        empty: string;
        summary: string;
    };
};

export default function WikiHistory({ entry, revisions, copy }: Props) {
    return (
        <>
            <Head title={`${copy.history}: ${entry.title}`} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={`/wiki/${entry.slug}`}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            {entry.title}
                        </Link>
                    </Button>
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <ClockCounterClockwiseIcon className="size-5" weight="fill" />
                        <span className="text-sm">{copy.wiki}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{copy.history}</h1>
                    <p className="text-sm text-muted-foreground">{entry.title}</p>
                </div>

                {revisions.length === 0 ? (
                    <div className="border border-border bg-muted/20 px-6 py-16 text-center">{copy.empty}</div>
                ) : (
                    <div className="flex flex-col gap-3">
                        {revisions.map((revision) => (
                            <Card key={revision.id}>
                                <CardContent className="flex flex-col gap-1 pt-(--card-spacing)">
                                    <Link
                                        href={`/wiki/${entry.slug}/history/${revision.id}`}
                                        className="font-medium hover:underline"
                                    >
                                        {revision.title}
                                    </Link>
                                    <p className="text-sm text-muted-foreground">
                                        {revision.created_for_humans}
                                        {revision.author ? ` · @${revision.author}` : ''}
                                    </p>
                                    {revision.summary ? (
                                        <p className="text-sm">
                                            {copy.summary}: {revision.summary}
                                        </p>
                                    ) : null}
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
