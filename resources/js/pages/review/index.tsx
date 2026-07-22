import { Form, Head, Link, usePage } from '@inertiajs/react';
import {
    ChatTextIcon,
    ClockIcon,
    PencilSimpleIcon,
    StarIcon,
    XIcon,
} from '@phosphor-icons/react';
import { useState } from 'react';

import InputError from '@/components/input-error';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { cn } from '@/lib/utils';
import { show as memberShow } from '@/routes/member';
import { store } from '@/routes/review';
import type { SharedPageProps } from '@/types';

type ReviewItem = {
    id: number;
    rating: number;
    body: string;
    version?: string | null;
    created_for_humans?: string | null;
    author: {
        name: string;
        username?: string | null;
        profile_photo_url?: string | null;
    };
};

type Props = {
    reviews: ReviewItem[];
    averageRating: number;
    numberOfReviews: number;
    gameVersions: Array<{ id: number; version: string; release_date: string | null }>;
    canCreate: boolean;
};

const ratingLabels: Record<number, string> = {
    1: 'Poor',
    2: 'Fair',
    3: 'Good',
    4: 'Great',
    5: 'Excellent',
};

function initials(name: string): string {
    return name
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0] ?? '')
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

function Stars({
    count,
    className,
    size = 'size-4',
}: {
    count: number;
    className?: string;
    size?: string;
}) {
    return (
        <span className={cn('inline-flex items-center gap-0.5', className)} aria-label={`${count} out of 5 stars`}>
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

export default function ReviewIndex({
    reviews,
    averageRating,
    numberOfReviews,
    gameVersions,
    canCreate,
}: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const [formOpen, setFormOpen] = useState(false);
    const [rating, setRating] = useState(0);
    const [body, setBody] = useState('');

    return (
        <>
            <Head title="Reviews" />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-wrap items-end justify-between gap-4">
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <StarIcon className="size-5" weight="fill" />
                            <span className="text-sm">Community</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">Game Reviews</h1>
                        <p className="text-sm text-muted-foreground">
                            See what trainers think of recent Pokémon 3D releases.
                        </p>
                    </div>

                    {canCreate && auth.user ? (
                        <Button
                            type="button"
                            onClick={() => setFormOpen((value) => ! value)}
                            variant={formOpen ? 'outline' : 'default'}
                        >
                            {formOpen ? (
                                <>
                                    <XIcon data-icon="inline-start" />
                                    Cancel
                                </>
                            ) : (
                                <>
                                    <PencilSimpleIcon data-icon="inline-start" weight="fill" />
                                    Write a review
                                </>
                            )}
                        </Button>
                    ) : null}
                </div>

                {formOpen && canCreate && auth.user ? (
                    <Card className="mb-8">
                        <CardHeader>
                            <CardTitle className="text-base font-semibold">Write a review</CardTitle>
                            <CardDescription>
                                Share your experience with a recent game version. Reviews help other trainers decide
                                what to try next.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Form
                                {...store.form()}
                                className="flex flex-col gap-4"
                                onSuccess={() => {
                                    setFormOpen(false);
                                    setRating(0);
                                    setBody('');
                                }}
                            >
                                {({ processing, errors }) => (
                                    <>
                                        <div className="flex flex-col gap-2">
                                            <Label htmlFor="gameversion">Game version</Label>
                                            <select
                                                id="gameversion"
                                                name="gameversion"
                                                required
                                                className="border-input h-8 w-full rounded-none border bg-transparent px-2.5 text-xs outline-none focus-visible:border-ring focus-visible:ring-1 focus-visible:ring-ring/50 dark:bg-input/30"
                                            >
                                                {gameVersions.length === 0 ? (
                                                    <option value="" disabled>
                                                        No versions available
                                                    </option>
                                                ) : (
                                                    gameVersions.map((version) => (
                                                        <option key={version.id} value={version.id}>
                                                            {version.version}
                                                            {version.release_date ? ` · ${version.release_date}` : ''}
                                                        </option>
                                                    ))
                                                )}
                                            </select>
                                            <InputError message={errors.gameversion} />
                                        </div>

                                        <div className="flex flex-col gap-2">
                                            <Label>Rating</Label>
                                            <div className="flex flex-wrap items-center gap-3">
                                                <div className="flex items-center gap-1">
                                                    {[1, 2, 3, 4, 5].map((value) => (
                                                        <button
                                                            key={value}
                                                            type="button"
                                                            title={ratingLabels[value]}
                                                            onClick={() => setRating(value)}
                                                            className={cn(
                                                                'rounded-none p-1 transition-colors',
                                                                rating >= value
                                                                    ? 'text-amber-500'
                                                                    : 'text-muted-foreground/40 hover:text-amber-500/70',
                                                            )}
                                                        >
                                                            <StarIcon className="size-7" weight="fill" />
                                                        </button>
                                                    ))}
                                                </div>
                                                <span className="text-sm text-muted-foreground">
                                                    {rating > 0 ? ratingLabels[rating] : 'Select a rating'}
                                                </span>
                                            </div>
                                            <input type="hidden" name="rating" value={rating || ''} required />
                                            <InputError message={errors.rating} />
                                        </div>

                                        <div className="flex flex-col gap-2">
                                            <Label htmlFor="body">Review</Label>
                                            <Textarea
                                                id="body"
                                                name="body"
                                                required
                                                minLength={10}
                                                maxLength={255}
                                                placeholder="What stood out in this version?"
                                                value={body}
                                                onChange={(event) => setBody(event.target.value)}
                                                className="min-h-28"
                                            />
                                            <div className="flex justify-between gap-2 text-xs text-muted-foreground">
                                                <span>10-255 characters</span>
                                                <span>{body.length}/255</span>
                                            </div>
                                            <InputError message={errors.body} />
                                        </div>

                                        <div className="flex flex-wrap justify-end gap-2">
                                            <Button
                                                type="button"
                                                variant="outline"
                                                onClick={() => setFormOpen(false)}
                                            >
                                                Cancel
                                            </Button>
                                            <Button type="submit" disabled={processing || rating < 1}>
                                                Submit review
                                            </Button>
                                        </div>
                                    </>
                                )}
                            </Form>
                        </CardContent>
                    </Card>
                ) : null}

                <div className="mb-8 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-sm font-medium text-muted-foreground">Average rating</CardTitle>
                            <div className="bg-primary/10 p-2 text-primary">
                                <StarIcon className="size-4" weight="fill" />
                            </div>
                        </CardHeader>
                        <CardContent className="flex flex-col gap-2">
                            <div className="text-3xl font-bold">{numberOfReviews > 0 ? averageRating.toFixed(1) : '—'}</div>
                            <Stars count={Math.round(averageRating)} />
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-sm font-medium text-muted-foreground">Total reviews</CardTitle>
                            <div className="bg-primary/10 p-2 text-primary">
                                <ChatTextIcon className="size-4" weight="fill" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-bold">{numberOfReviews}</div>
                            <p className="mt-1 text-sm text-muted-foreground">
                                {numberOfReviews === 1 ? 'review submitted' : 'reviews submitted'}
                            </p>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between pb-2">
                            <CardTitle className="text-sm font-medium text-muted-foreground">Versions covered</CardTitle>
                            <div className="bg-primary/10 p-2 text-primary">
                                <PencilSimpleIcon className="size-4" weight="fill" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-bold">
                                {new Set(reviews.map((review) => review.version).filter(Boolean)).size}
                            </div>
                            <p className="mt-1 text-sm text-muted-foreground">distinct game versions</p>
                        </CardContent>
                    </Card>
                </div>

                {reviews.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <StarIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">No reviews yet</div>
                        <p className="max-w-md text-sm text-muted-foreground">
                            Be the first to share your thoughts on a recent game version.
                        </p>
                        {canCreate && auth.user ? (
                            <Button type="button" onClick={() => setFormOpen(true)}>
                                <PencilSimpleIcon data-icon="inline-start" weight="fill" />
                                Write a review
                            </Button>
                        ) : null}
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        {reviews.map((review) => {
                            const displayName = review.author.username || review.author.name;
                            const profileUrl = review.author.username
                                ? memberShow.url(review.author.username)
                                : null;

                            return (
                                <Card key={review.id} className="h-full">
                                    <CardHeader className="flex flex-row items-start gap-3">
                                        <Avatar className="size-10">
                                            {review.author.profile_photo_url ? (
                                                <AvatarImage
                                                    src={review.author.profile_photo_url}
                                                    alt={displayName}
                                                />
                                            ) : null}
                                            <AvatarFallback>{initials(displayName)}</AvatarFallback>
                                        </Avatar>
                                        <div className="min-w-0 flex-1">
                                            {profileUrl ? (
                                                <Link
                                                    href={profileUrl}
                                                    className="truncate text-sm font-medium text-foreground hover:text-primary"
                                                >
                                                    {displayName}
                                                </Link>
                                            ) : (
                                                <div className="truncate text-sm font-medium">{displayName}</div>
                                            )}
                                            <div className="mt-1 flex flex-wrap items-center gap-2">
                                                <Stars count={review.rating} />
                                                {review.version ? (
                                                    <Badge variant="secondary">{review.version}</Badge>
                                                ) : null}
                                            </div>
                                        </div>
                                    </CardHeader>
                                    <CardContent className="flex flex-col gap-3">
                                        <p className="text-sm leading-relaxed text-muted-foreground">{review.body}</p>
                                        {review.created_for_humans ? (
                                            <div className="flex items-center gap-1.5 text-xs text-muted-foreground">
                                                <ClockIcon className="size-3.5" weight="fill" />
                                                <span>{review.created_for_humans}</span>
                                            </div>
                                        ) : null}
                                    </CardContent>
                                </Card>
                            );
                        })}
                    </div>
                )}
            </div>
        </>
    );
}
