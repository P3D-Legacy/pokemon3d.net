import { Head, Link } from '@inertiajs/react';
import {
    PackageIcon,
    CaretRightIcon,
    DownloadSimpleIcon,
    HeartIcon,
    StarIcon,
} from '@phosphor-icons/react';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import { index as resourceIndex } from '@/routes/resource';
import type { Paginated } from '@/types';

type ResourceCard = {
    uuid: string;
    name: string;
    brief: string;
    version: string;
    category: string;
    rating: { average: number; stars: number; count: number };
    likes: number;
    downloads: number;
    updated_at: string;
    created_at: string;
    author: { username: string; name: string; profile_photo_url: string };
    url: string;
};

type Props = {
    resources: Paginated<ResourceCard>;
    copy: {
        title: string;
        resources: string;
        description: string;
        rating: string;
        likes: string;
        downloads: string;
        updated: string;
        nothingFound: string;
    };
};

function Stars({ count }: { count: number }) {
    const { t } = useTranslations();

    return (
        <span className="inline-flex items-center gap-0.5" aria-label={t(':count out of 5 stars', { count })}>
            {Array.from({ length: 5 }, (_, index) => (
                <StarIcon
                    key={index}
                    className={cn('size-3.5', index < count ? 'text-amber-500' : 'text-muted-foreground/35')}
                    weight="fill"
                />
            ))}
        </span>
    );
}

function initials(name: string): string {
    return name
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0] ?? '')
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

function paginationLabel(label: string): string {
    return label
        .replace(/&laquo;/g, '«')
        .replace(/&raquo;/g, '»')
        .replace(/&nbsp;/g, ' ')
        .replace(/<[^>]*>/g, '')
        .trim();
}

export default function ResourcesFollowing({ resources, copy }: Props) {
    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <nav className="mb-6 flex flex-wrap items-center gap-2 text-sm text-muted-foreground">
                    <Link href={resourceIndex.url()} className="hover:text-foreground">
                        {copy.resources}
                    </Link>
                    <span>/</span>
                    <span className="text-foreground">{copy.title}</span>
                </nav>

                <div className="mb-8 flex flex-col gap-2">
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <PackageIcon className="size-5" weight="fill" />
                        <span className="text-sm">{copy.resources}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                    <p className="text-sm text-muted-foreground">{copy.description}</p>
                </div>

                {resources.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <PackageIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">{copy.nothingFound}</div>
                        <Button size="sm" asChild>
                            <Link href={resourceIndex.url()}>{copy.resources}</Link>
                        </Button>
                    </div>
                ) : (
                    <div className="flex flex-col gap-3">
                        {resources.data.map((resource) => (
                            <Link
                                key={resource.uuid}
                                href={resource.url}
                                className="group flex min-w-0 flex-col gap-3 border border-border bg-card p-4 transition-colors hover:border-primary hover:bg-primary/5 sm:flex-row sm:items-center"
                            >
                                <Avatar className="size-11 shrink-0">
                                    <AvatarImage
                                        src={resource.author.profile_photo_url}
                                        alt={resource.author.username}
                                    />
                                    <AvatarFallback>
                                        {initials(resource.author.name || resource.author.username)}
                                    </AvatarFallback>
                                </Avatar>

                                <div className="min-w-0 flex-1">
                                    <div className="flex flex-wrap items-center gap-2">
                                        <span className="truncate font-medium">{resource.name}</span>
                                        <Badge variant="secondary">{resource.version}</Badge>
                                        <Badge variant="outline">{resource.category}</Badge>
                                    </div>
                                    <p className="mt-1 truncate text-sm text-muted-foreground">
                                        {resource.author.username} · {resource.created_at}
                                    </p>
                                    <p className="mt-1 line-clamp-2 text-sm text-muted-foreground">{resource.brief}</p>
                                </div>

                                <div className="flex shrink-0 flex-col gap-1.5 text-xs text-muted-foreground sm:items-end">
                                    <div className="flex items-center gap-1.5">
                                        <Stars count={resource.rating.stars} />
                                        <span>
                                            {resource.rating.average} ({resource.rating.count})
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-1.5">
                                        <HeartIcon className="size-3.5" weight="fill" />
                                        <span>
                                            {resource.likes} {copy.likes.toLowerCase()}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-1.5">
                                        <DownloadSimpleIcon className="size-3.5" weight="fill" />
                                        <span>
                                            {resource.downloads} {copy.downloads.toLowerCase()}
                                        </span>
                                    </div>
                                    <span>
                                        {copy.updated} {resource.updated_at}
                                    </span>
                                </div>

                                <CaretRightIcon className="hidden size-4 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-0.5 group-hover:text-primary sm:block" />
                            </Link>
                        ))}
                    </div>
                )}

                {resources.links.length > 3 ? (
                    <div className="mt-4 flex flex-wrap items-center justify-center gap-2">
                        {resources.links.map((link, index) =>
                            link.url ? (
                                <Button
                                    key={`${link.label}-${index}`}
                                    variant={link.active ? 'default' : 'outline'}
                                    size="sm"
                                    asChild
                                >
                                    <Link
                                        href={link.url}
                                        className={cn(! link.active && 'text-muted-foreground')}
                                    >
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
