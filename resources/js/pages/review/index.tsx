import { Form, Head, usePage } from '@inertiajs/react';
import { useState } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
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

export default function ReviewIndex({
    reviews,
    averageRating,
    numberOfReviews,
    gameVersions,
    canCreate,
}: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const [open, setOpen] = useState(false);

    return (
        <>
            <Head title="Reviews" />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-xl font-semibold">Game Reviews</h1>
                    {canCreate && auth.user && (
                        <Button type="button" variant="brand" onClick={() => setOpen((value) => ! value)}>
                            Write a review
                        </Button>
                    )}
                </div>

                {open && (
                    <Form {...store.form()} className="mb-8 space-y-4 rounded-lg border bg-white p-6 dark:bg-slate-900" onSuccess={() => setOpen(false)}>
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="gameversion">Game version</Label>
                                    <select
                                        id="gameversion"
                                        name="gameversion"
                                        required
                                        className="border-input h-9 rounded-md border bg-transparent px-3 text-sm"
                                    >
                                        {gameVersions.map((version) => (
                                            <option key={version.id} value={version.id}>
                                                {version.version}
                                            </option>
                                        ))}
                                    </select>
                                    <InputError message={errors.gameversion} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="rating">Rating (1-5)</Label>
                                    <input
                                        id="rating"
                                        name="rating"
                                        type="number"
                                        min={1}
                                        max={5}
                                        required
                                        className="border-input h-9 rounded-md border bg-transparent px-3 text-sm"
                                    />
                                    <InputError message={errors.rating} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="body">Review</Label>
                                    <textarea
                                        id="body"
                                        name="body"
                                        required
                                        minLength={10}
                                        maxLength={255}
                                        className="border-input min-h-24 rounded-md border bg-transparent px-3 py-2 text-sm"
                                    />
                                    <InputError message={errors.body} />
                                </div>
                                <Button type="submit" variant="brand" disabled={processing}>
                                    Submit
                                </Button>
                            </>
                        )}
                    </Form>
                )}

                <div className="mb-6 rounded-lg border bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                    <div className="text-lg font-bold">Overall Game Reviews</div>
                    <div className="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        {averageRating} ({numberOfReviews})
                    </div>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {reviews.map((review) => (
                        <div key={review.id} className="rounded-lg border bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                            <div className="mb-2 flex items-center gap-2">
                                <img
                                    src={review.author.profile_photo_url || '/img/TreeLogoSmall.png'}
                                    alt={review.author.username || review.author.name}
                                    className="size-8 rounded-full object-cover"
                                />
                                <span className="text-sm text-green-500">{review.author.username || review.author.name}</span>
                            </div>
                            <div className="text-sm font-medium">{'★'.repeat(review.rating)}</div>
                            <div className="my-1 text-lg font-bold">{review.version}</div>
                            <p className="text-sm text-slate-700 dark:text-slate-200">{review.body}</p>
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}
