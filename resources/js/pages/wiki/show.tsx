import { Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, BookOpenIcon, ClockCounterClockwiseIcon, PencilSimpleIcon } from '@phosphor-icons/react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';

type Props = {
    entry: {
        kind: 'page';
        uuid: string;
        title: string;
        slug: string;
        body_html: string;
        published: boolean;
        updated_at: string | null;
        author: { username: string | null; profile_photo_url: string | null };
        tags: string[];
        permissions: { can_update: boolean; can_delete: boolean };
    };
    copy: {
        wiki: string;
        edit: string;
        history: string;
        updated: string;
        unpublished: string;
    };
};

export default function WikiShow({ entry, copy }: Props) {
    return (
        <>
            <Head title={entry.title} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href="/wiki">
                            <ArrowLeftIcon data-icon="inline-start" />
                            {copy.wiki}
                        </Link>
                    </Button>

                    <div className="flex flex-wrap items-center gap-2 text-muted-foreground">
                        <BookOpenIcon className="size-5" weight="fill" />
                        <span className="text-sm">{copy.wiki}</span>
                        {entry.tags.map((tag) => (
                            <Badge key={tag} variant="secondary">
                                {tag}
                            </Badge>
                        ))}
                        {! entry.published ? <Badge variant="outline">{copy.unpublished}</Badge> : null}
                    </div>

                    <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <h1 className="text-3xl font-semibold tracking-tight break-words md:text-4xl">{entry.title}</h1>
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

                    {entry.updated_at ? (
                        <p className="text-sm text-muted-foreground">
                            {copy.updated} {entry.updated_at}
                            {entry.author.username ? ` · @${entry.author.username}` : ''}
                        </p>
                    ) : null}
                </div>

                <Card>
                    <CardContent className="pt-(--card-spacing)">
                        <article
                            className="prose dark:prose-invert max-w-none"
                            dangerouslySetInnerHTML={{ __html: entry.body_html }}
                        />
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
