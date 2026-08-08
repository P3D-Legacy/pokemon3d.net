import { Form, Head, Link, usePage } from '@inertiajs/react';
import {
    BookmarkSimpleIcon,
    BooksIcon,
    DownloadSimpleIcon,
    DotsThreeIcon,
    EyeIcon,
    HeartIcon,
    PencilSimpleIcon,
    PlusIcon,
    StarIcon,
    TrashIcon,
} from '@phosphor-icons/react';
import { useState, type ReactNode } from 'react';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import { deleteMethod, edit, follow, like, rate, index as resourceIndex } from '@/routes/resource';
import { create as createUpdate } from '@/routes/resource/updates';
import type { SharedPageProps } from '@/types';

type Props = {
    resource: {
        uuid: string;
        name: string;
        brief: string;
        description_html: string;
        version: string;
        category: { name: string; slug: string; url: string } | null;
        rating: { average: number; stars: number; count: number };
        likes: { count: number; liked: boolean };
        follows: { count: number; followed: boolean };
        downloads: number;
        views: string;
        created_at: string;
        updated_at: string;
        author: { username: string; profile_photo_url: string; url: string };
        updates: Array<{
            id: number;
            title: string;
            description_excerpt: string;
            description_html: string;
            game_version: string | null;
            downloads: number;
            created_at: string;
            download_url: string;
        }>;
        reviews: Array<{
            id: number;
            body: string;
            rating: number;
            created_at: string;
            author: { username: string | null; profile_photo_url: string; url: string | null };
        }>;
        latest_update_id: number | null;
        permissions: {
            can_update: boolean;
            can_delete: boolean;
            can_post_update: boolean;
            can_rate: boolean;
            can_like: boolean;
            can_follow: boolean;
        };
    };
    copy: {
        resources: string;
        leaveRating: string;
        download: string;
        postUpdate: string;
        edit: string;
        delete: string;
        author: string;
        rating: string;
        downloads: string;
        views: string;
        created: string;
        updated: string;
        updates: string;
        latestReviews: string;
        nothingFound: string;
        like: string;
        unlike: string;
        follow: string;
        unfollow: string;
        downloadDisclaimerTitle: string;
        downloadDisclaimerBody: string;
        downloadDisclaimerCancel: string;
        downloadDisclaimerConfirm: string;
    };
};

function Stars({ count, size = 'size-3.5' }: { count: number; size?: string }) {
    const { t } = useTranslations();

    return (
        <span className="inline-flex items-center gap-0.5" aria-label={t(':count out of 5 stars', { count })}>
            {Array.from({ length: 5 }, (_, index) => (
                <StarIcon
                    key={index}
                    className={cn(size, index < count ? 'text-amber-500' : 'text-muted-foreground/35')}
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

export default function ResourceShow({ resource, copy }: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const { t } = useTranslations();
    const [activeUpdate, setActiveUpdate] = useState<(typeof resource.updates)[number] | null>(null);
    const [pendingDownloadUrl, setPendingDownloadUrl] = useState<string | null>(null);
    const latestUpdate =
        resource.updates.find((update) => update.id === resource.latest_update_id) ?? resource.updates[0];
    const isOwner =
        resource.permissions.can_update || resource.permissions.can_delete || resource.permissions.can_post_update;

    const confirmDownload = () => {
        if (! pendingDownloadUrl) {
            return;
        }

        const downloadUrl = pendingDownloadUrl;

        setPendingDownloadUrl(null);
        setActiveUpdate(null);
        window.location.assign(downloadUrl);
    };

    return (
        <>
            <Head title={resource.name} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <nav className="mb-6 flex flex-wrap items-center gap-2 text-sm text-muted-foreground">
                    <Link href={resourceIndex.url()} className="hover:text-foreground">
                        {copy.resources}
                    </Link>
                    {resource.category ? (
                        <>
                            <span>/</span>
                            <Link href={resource.category.url} className="hover:text-foreground">
                                {resource.category.name}
                            </Link>
                        </>
                    ) : null}
                    <span>/</span>
                    <span className="text-foreground">{resource.name}</span>
                </nav>

                <div className="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div className="flex min-w-0 flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <BooksIcon className="size-5" weight="fill" />
                            <span className="text-sm">{copy.resources}</span>
                        </div>
                        <h1 className="flex flex-wrap items-center gap-2 text-3xl font-semibold tracking-tight">
                            <span>{resource.name}</span>
                            <Badge variant="secondary">{resource.version}</Badge>
                        </h1>
                        <p className="text-sm text-muted-foreground">{resource.brief}</p>
                    </div>

                    <div className="flex flex-wrap items-center gap-2">
                        {auth.user && resource.permissions.can_like ? (
                            <Form {...like.form(resource.uuid)}>
                                {({ processing }) => (
                                    <Button
                                        type="submit"
                                        variant={resource.likes.liked ? 'like' : 'outline'}
                                        size="sm"
                                        disabled={processing}
                                    >
                                        <HeartIcon
                                            data-icon="inline-start"
                                            weight={resource.likes.liked ? 'fill' : 'regular'}
                                        />
                                        {resource.likes.liked ? copy.unlike : copy.like} ({resource.likes.count})
                                    </Button>
                                )}
                            </Form>
                        ) : null}
                        {auth.user && resource.permissions.can_follow ? (
                            <Form {...follow.form(resource.uuid)}>
                                {({ processing }) => (
                                    <Button
                                        type="submit"
                                        variant={resource.follows.followed ? 'secondary' : 'outline'}
                                        size="sm"
                                        disabled={processing}
                                    >
                                        <BookmarkSimpleIcon
                                            data-icon="inline-start"
                                            weight={resource.follows.followed ? 'fill' : 'regular'}
                                        />
                                        {resource.follows.followed ? copy.unfollow : copy.follow} (
                                        {resource.follows.count})
                                    </Button>
                                )}
                            </Form>
                        ) : null}
                        {auth.user && resource.permissions.can_rate ? (
                            <Button size="sm" variant="secondary" asChild>
                                <Link href={rate.url(resource.uuid)}>
                                    <StarIcon data-icon="inline-start" weight="fill" />
                                    {copy.leaveRating}
                                </Link>
                            </Button>
                        ) : null}
                        {latestUpdate ? (
                            <Button
                                size="sm"
                                type="button"
                                onClick={() => setPendingDownloadUrl(latestUpdate.download_url)}
                            >
                                <DownloadSimpleIcon data-icon="inline-start" weight="fill" />
                                {copy.download}
                            </Button>
                        ) : null}
                        {auth.user && isOwner ? (
                            <DropdownMenu>
                                <DropdownMenuTrigger asChild>
                                    <Button type="button" variant="outline" size="icon-sm" aria-label={t('Manage resource')}>
                                        <DotsThreeIcon weight="bold" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    {resource.permissions.can_post_update ? (
                                        <DropdownMenuItem asChild>
                                            <Link href={createUpdate.url(resource.uuid)}>
                                                <PlusIcon />
                                                {copy.postUpdate}
                                            </Link>
                                        </DropdownMenuItem>
                                    ) : null}
                                    {resource.permissions.can_update ? (
                                        <DropdownMenuItem asChild>
                                            <Link href={edit.url(resource.uuid)}>
                                                <PencilSimpleIcon />
                                                {copy.edit}
                                            </Link>
                                        </DropdownMenuItem>
                                    ) : null}
                                    {resource.permissions.can_delete ? (
                                        <DropdownMenuItem asChild variant="destructive">
                                            <Link href={deleteMethod.url(resource.uuid)}>
                                                <TrashIcon />
                                                {copy.delete}
                                            </Link>
                                        </DropdownMenuItem>
                                    ) : null}
                                </DropdownMenuContent>
                            </DropdownMenu>
                        ) : null}
                    </div>
                </div>

                <div className="mb-8 grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <Card className="lg:col-span-2">
                        <CardContent className="pt-(--card-spacing)">
                            <div
                                className="prose dark:prose-invert max-w-none"
                                dangerouslySetInnerHTML={{ __html: resource.description_html }}
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle className="text-sm font-medium text-muted-foreground">{t('Details')}</CardTitle>
                        </CardHeader>
                        <CardContent className="flex flex-col gap-3 text-sm">
                            <DetailRow label={copy.author}>
                                <Link href={resource.author.url} className="font-medium text-primary hover:underline">
                                    {resource.author.username}
                                </Link>
                            </DetailRow>
                            <DetailRow label={copy.rating}>
                                <span className="inline-flex items-center gap-1.5">
                                    <Stars count={resource.rating.stars} />
                                    {resource.rating.average} ({resource.rating.count})
                                </span>
                            </DetailRow>
                            <DetailRow label={copy.downloads}>{resource.downloads}</DetailRow>
                            <DetailRow label={copy.views}>
                                <span className="inline-flex items-center gap-1.5">
                                    <EyeIcon className="size-3.5 text-muted-foreground" weight="fill" />
                                    {resource.views}
                                </span>
                            </DetailRow>
                            <DetailRow label={copy.created}>{resource.created_at}</DetailRow>
                            <DetailRow label={copy.updated}>{resource.updated_at}</DetailRow>
                        </CardContent>
                    </Card>
                </div>

                <section className="mb-8 flex flex-col gap-4">
                    <h2 className="text-lg font-semibold tracking-tight">{copy.updates}</h2>
                    {resource.updates.length === 0 ? (
                        <div className="border border-border bg-muted/20 px-6 py-10 text-center text-sm text-muted-foreground">
                            {copy.nothingFound}
                        </div>
                    ) : (
                        <div className="flex flex-col gap-3">
                            {resource.updates.map((update) => (
                                <button
                                    key={update.id}
                                    type="button"
                                    onClick={() => setActiveUpdate(update)}
                                    className="flex min-w-0 flex-col gap-2 border border-border bg-card p-4 text-left transition-colors hover:border-primary hover:bg-primary/5 sm:flex-row sm:items-center sm:justify-between"
                                >
                                    <div className="min-w-0 flex-1">
                                        <div className="font-medium text-primary">{update.title}</div>
                                        <p className="mt-1 line-clamp-2 text-sm text-muted-foreground">
                                            {update.description_excerpt}
                                        </p>
                                    </div>
                                    <div className="flex flex-wrap items-center gap-2 text-xs text-muted-foreground sm:justify-end">
                                        {update.game_version ? <Badge variant="outline">{update.game_version}</Badge> : null}
                                        <span>{update.created_at}</span>
                                        <span>
                                            {update.downloads} {copy.downloads.toLowerCase()}
                                        </span>
                                    </div>
                                </button>
                            ))}
                        </div>
                    )}
                </section>

                <section className="flex flex-col gap-4">
                    <h2 className="text-lg font-semibold tracking-tight">{copy.latestReviews}</h2>
                    {resource.reviews.length === 0 ? (
                        <div className="border border-border bg-muted/20 px-6 py-10 text-center text-sm text-muted-foreground">
                            {copy.nothingFound}
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            {resource.reviews.map((review) => {
                                const displayName = review.author.username || t('Anonymous');

                                return (
                                    <Card key={review.id}>
                                        <CardHeader className="flex flex-row items-start gap-3">
                                            <Avatar className="size-10">
                                                <AvatarImage
                                                    src={review.author.profile_photo_url}
                                                    alt={displayName}
                                                />
                                                <AvatarFallback>{initials(displayName)}</AvatarFallback>
                                            </Avatar>
                                            <div className="min-w-0 flex-1">
                                                {review.author.url ? (
                                                    <Link
                                                        href={review.author.url}
                                                        className="text-sm font-medium hover:text-primary"
                                                    >
                                                        {displayName}
                                                    </Link>
                                                ) : (
                                                    <div className="text-sm font-medium">{displayName}</div>
                                                )}
                                                <div className="mt-1 flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                                                    <Stars count={review.rating} />
                                                    <span>{review.created_at}</span>
                                                </div>
                                            </div>
                                        </CardHeader>
                                        <CardContent>
                                            <p className="text-sm leading-relaxed text-muted-foreground">{review.body}</p>
                                        </CardContent>
                                    </Card>
                                );
                            })}
                        </div>
                    )}
                </section>
            </div>

            <Dialog open={activeUpdate !== null} onOpenChange={(open) => ! open && setActiveUpdate(null)}>
                <DialogContent className="sm:max-w-xl" showCloseButton>
                    {activeUpdate ? (
                        <>
                            <DialogHeader>
                                <DialogTitle>{activeUpdate.title}</DialogTitle>
                                <DialogDescription>
                                    {[activeUpdate.game_version, activeUpdate.created_at].filter(Boolean).join(' · ')}
                                </DialogDescription>
                            </DialogHeader>
                            <div
                                className="prose dark:prose-invert max-h-[50vh] max-w-none overflow-y-auto"
                                dangerouslySetInnerHTML={{ __html: activeUpdate.description_html }}
                            />
                            <DialogFooter>
                                <Button
                                    type="button"
                                    onClick={() => setPendingDownloadUrl(activeUpdate.download_url)}
                                >
                                    <DownloadSimpleIcon data-icon="inline-start" weight="fill" />
                                    {copy.download}
                                </Button>
                            </DialogFooter>
                        </>
                    ) : null}
                </DialogContent>
            </Dialog>

            <Dialog
                open={pendingDownloadUrl !== null}
                onOpenChange={(open) => ! open && setPendingDownloadUrl(null)}
            >
                <DialogContent className="sm:max-w-md" showCloseButton>
                    <DialogHeader>
                        <DialogTitle>{copy.downloadDisclaimerTitle}</DialogTitle>
                        <DialogDescription>{copy.downloadDisclaimerBody}</DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button type="button" variant="outline" onClick={() => setPendingDownloadUrl(null)}>
                            {copy.downloadDisclaimerCancel}
                        </Button>
                        <Button type="button" onClick={confirmDownload}>
                            <DownloadSimpleIcon data-icon="inline-start" weight="fill" />
                            {copy.downloadDisclaimerConfirm}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
}

function DetailRow({ label, children }: { label: string; children: ReactNode }) {
    return (
        <div className="flex items-start justify-between gap-3">
            <span className="text-muted-foreground">{label}</span>
            <div className="text-right font-medium">{children}</div>
        </div>
    );
}
