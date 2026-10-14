import { Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, ClockCounterClockwiseIcon } from '@phosphor-icons/react';

import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { cn } from '@/lib/utils';

type DiffLine = {
    type: 'equal' | 'add' | 'remove';
    text: string;
};

type Props = {
    entry: {
        title: string;
        slug: string;
    };
    revision: {
        id: number;
        title: string;
        summary: string | null;
        created_at: string | null;
        author: string | null;
    };
    diff: DiffLine[];
    copy: {
        wiki: string;
        history: string;
        revision: string;
    };
};

export default function WikiRevision({ entry, revision, diff, copy }: Props) {
    return (
        <>
            <Head title={`${copy.revision}: ${revision.title}`} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={`/wiki/${entry.slug}/history`}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            {copy.history}
                        </Link>
                    </Button>
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <ClockCounterClockwiseIcon className="size-5" weight="fill" />
                        <span className="text-sm">{copy.wiki}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{revision.title}</h1>
                    <p className="text-sm text-muted-foreground">
                        {revision.created_at}
                        {revision.author ? ` · @${revision.author}` : ''}
                    </p>
                    {revision.summary ? <p className="text-sm">{revision.summary}</p> : null}
                </div>

                <Card>
                    <CardContent className="overflow-x-auto pt-(--card-spacing)">
                        <pre className="font-mono text-xs leading-6">
                            {diff.map((line, index) => (
                                <div
                                    key={`${line.type}-${index}`}
                                    className={cn(
                                        'px-2',
                                        line.type === 'add' && 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300',
                                        line.type === 'remove' && 'bg-red-500/15 text-red-700 dark:text-red-300',
                                    )}
                                >
                                    {line.type === 'add' ? '+ ' : line.type === 'remove' ? '- ' : '  '}
                                    {line.text}
                                </div>
                            ))}
                        </pre>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
