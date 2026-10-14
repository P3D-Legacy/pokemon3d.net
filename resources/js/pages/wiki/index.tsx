import { Head, Link, router } from '@inertiajs/react';
import { BookOpenIcon, MagnifyingGlassIcon, PlusIcon } from '@phosphor-icons/react';
import { FormEvent, useState } from 'react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { cn, paginationLabel } from '@/lib/utils';
import type { Paginated } from '@/types';

type WikiCard = {
    kind: string;
    title: string;
    slug: string;
    excerpt: string;
    url: string;
    updated_at: string | null;
    tags: string[];
};

type Props = {
    entries: Paginated<WikiCard>;
    filters: {
        q: string;
        tag: string;
        kind: string;
    };
    tags: string[];
    canCreate: boolean;
    legacyWikiUrl: string | null;
    copy: {
        title: string;
        subtitle: string;
        search: string;
        searchPlaceholder: string;
        create: string;
        nothingToShow: string;
        allKinds: string;
        pages: string;
        pokemon: string;
        moves: string;
        items: string;
        abilities: string;
        legacyNotice: string;
        updated: string;
    };
};

const kinds = [
    { id: '', labelKey: 'allKinds' },
    { id: 'page', labelKey: 'pages' },
    { id: 'pokemon', labelKey: 'pokemon' },
    { id: 'move', labelKey: 'moves' },
    { id: 'item', labelKey: 'items' },
    { id: 'ability', labelKey: 'abilities' },
] as const;

function wikiQuery(filters: Props['filters'], overrides: Partial<Props['filters']> = {}): string {
    const params = new URLSearchParams();
    const next = { ...filters, ...overrides };

    if (next.q) {
        params.set('q', next.q);
    }

    if (next.kind) {
        params.set('kind', next.kind);
    }

    if (next.tag) {
        params.set('tag', next.tag);
    }

    const query = params.toString();

    return query ? `/wiki?${query}` : '/wiki';
}

export default function WikiIndex({ entries, filters, tags, canCreate, legacyWikiUrl, copy }: Props) {
    const [query, setQuery] = useState(filters.q);

    const search = (event: FormEvent) => {
        event.preventDefault();
        router.get(
            '/wiki',
            { q: query, kind: filters.kind, tag: filters.tag },
            { preserveState: true, replace: true },
        );
    };

    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-4">
                    <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div className="flex flex-col gap-2">
                            <div className="flex items-center gap-2 text-muted-foreground">
                                <BookOpenIcon className="size-5" weight="fill" />
                                <span className="text-sm">{copy.title}</span>
                            </div>
                            <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                            <p className="max-w-2xl text-sm text-muted-foreground">{copy.subtitle}</p>
                        </div>
                        {canCreate ? (
                            <Button asChild>
                                <Link href="/wiki/create">
                                    <PlusIcon data-icon="inline-start" />
                                    {copy.create}
                                </Link>
                            </Button>
                        ) : null}
                    </div>

                    {legacyWikiUrl ? (
                        <p className="text-sm text-muted-foreground">
                            {copy.legacyNotice}{' '}
                            <a href={legacyWikiUrl} className="text-primary hover:underline">
                                {legacyWikiUrl}
                            </a>
                        </p>
                    ) : null}

                    <form onSubmit={search} className="flex flex-col gap-3 sm:flex-row">
                        <div className="relative grow">
                            <MagnifyingGlassIcon className="pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                value={query}
                                onChange={(event) => setQuery(event.target.value)}
                                placeholder={copy.searchPlaceholder}
                                className="pl-8"
                            />
                        </div>
                        <Button type="submit">{copy.search}</Button>
                    </form>

                    <div className="flex flex-wrap gap-2">
                        {kinds.map((kind) => (
                            <Button
                                key={kind.id || 'all'}
                                size="sm"
                                variant={filters.kind === kind.id ? 'default' : 'outline'}
                                asChild
                            >
                                <Link href={wikiQuery(filters, { kind: kind.id })}>
                                    {copy[kind.labelKey]}
                                </Link>
                            </Button>
                        ))}
                    </div>

                    {tags.length > 0 ? (
                        <div className="flex flex-wrap gap-2">
                            {tags.map((tag) => (
                                <Badge key={tag} variant={filters.tag === tag ? 'default' : 'secondary'} asChild>
                                    <Link href={wikiQuery(filters, { tag })}>
                                        {tag}
                                    </Link>
                                </Badge>
                            ))}
                        </div>
                    ) : null}
                </div>

                {entries.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <BookOpenIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">{copy.nothingToShow}</div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                        {entries.data.map((entry) => (
                            <Card key={`${entry.kind}-${entry.slug}`}>
                                <CardHeader className="gap-2">
                                    <div className="flex items-center gap-2">
                                        <Badge variant="secondary">{entry.kind}</Badge>
                                        {entry.tags.map((tag) => (
                                            <Badge key={tag} variant="outline">
                                                {tag}
                                            </Badge>
                                        ))}
                                    </div>
                                    <CardTitle className="text-lg">
                                        <Link href={entry.url} className="hover:underline">
                                            {entry.title}
                                        </Link>
                                    </CardTitle>
                                </CardHeader>
                                <CardContent className="flex flex-col gap-2 text-sm text-muted-foreground">
                                    <p>{entry.excerpt}</p>
                                    {entry.updated_at ? (
                                        <p>
                                            {copy.updated} {entry.updated_at}
                                        </p>
                                    ) : null}
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}

                {entries.links.length > 3 ? (
                    <div className="mt-8 flex flex-wrap items-center justify-center gap-2">
                        {entries.links.map((link, index) =>
                            link.url ? (
                                <Button
                                    key={`${link.label}-${index}`}
                                    variant={link.active ? 'default' : 'outline'}
                                    size="sm"
                                    asChild
                                >
                                    <Link href={link.url} className={cn(! link.active && 'text-muted-foreground')}>
                                        {paginationLabel(link.label)}
                                    </Link>
                                </Button>
                            ) : (
                                <Button key={`${link.label}-${index}`} variant="outline" size="sm" disabled>
                                    {paginationLabel(link.label)}
                                </Button>
                            ),
                        )}
                    </div>
                ) : null}
            </div>
        </>
    );
}
