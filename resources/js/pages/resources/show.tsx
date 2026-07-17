import { Form, Head, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';

import { Button } from '@/components/ui/button';
import { deleteMethod, edit, like, rate, index as resourceIndex } from '@/routes/resource';
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
    };
};

function Stars({ count }: { count: number }) {
    return (
        <span className="inline-flex text-yellow-500" aria-hidden>
            {Array.from({ length: 5 }, (_, index) => (
                <span key={index} className={index < count ? 'opacity-100' : 'opacity-30'}>
                    ★
                </span>
            ))}
        </span>
    );
}

export default function ResourceShow({ resource, copy }: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const [menuOpen, setMenuOpen] = useState(false);
    const [activeUpdate, setActiveUpdate] = useState<(typeof resource.updates)[number] | null>(null);
    const latestUpdate = resource.updates.find((update) => update.id === resource.latest_update_id) ?? resource.updates[0];
    const isOwner =
        resource.permissions.can_update || resource.permissions.can_delete || resource.permissions.can_post_update;

    return (
        <>
            <Head title={resource.name} />

            <div className="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
                <nav className="mb-6 px-2 text-sm text-slate-500 sm:px-0">
                    <Link href={resourceIndex.url()} className="hover:underline">
                        {copy.resources}
                    </Link>
                    {resource.category && (
                        <>
                            <span className="mx-2">/</span>
                            <Link href={resource.category.url} className="hover:underline">
                                {resource.category.name}
                            </Link>
                        </>
                    )}
                    <span className="mx-2">/</span>
                    <span className="text-slate-700 dark:text-slate-300">{resource.name}</span>
                </nav>

                <div className="mb-4 grid grid-rows-2 gap-4 px-2 sm:grid-flow-col sm:grid-cols-3 sm:grid-rows-none sm:px-0">
                    <div className="text-2xl sm:col-span-2 dark:text-white">
                        {resource.name} <span className="text-slate-400 dark:text-slate-500">{resource.version}</span>
                    </div>
                    <div className="flex justify-end gap-1">
                        {auth.user && resource.permissions.can_like && (
                            <Form {...like.form(resource.uuid)}>
                                {({ processing }) => (
                                    <Button type="submit" variant="secondary" size="sm" disabled={processing}>
                                        {resource.likes.liked ? copy.unlike : copy.like} ({resource.likes.count})
                                    </Button>
                                )}
                            </Form>
                        )}
                        {auth.user && resource.permissions.can_rate && (
                            <Button asChild size="sm" className="bg-sky-600 text-sky-100 hover:bg-sky-700">
                                <Link href={rate.url(resource.uuid)}>{copy.leaveRating}</Link>
                            </Button>
                        )}
                        {latestUpdate && (
                            <Button asChild variant="brand" size="sm">
                                <a href={latestUpdate.download_url}>{copy.download}</a>
                            </Button>
                        )}
                        {auth.user && isOwner && (
                            <div className="relative">
                                <Button type="button" variant="secondary" size="sm" onClick={() => setMenuOpen((value) => ! value)}>
                                    ⋯
                                </Button>
                                {menuOpen && (
                                    <div className="absolute right-0 z-10 mt-2 w-48 rounded-md border border-slate-200 bg-white py-2 shadow-md dark:border-slate-800 dark:bg-slate-900">
                                        {resource.permissions.can_post_update && (
                                            <Link
                                                href={createUpdate.url(resource.uuid)}
                                                className="block px-4 py-2 text-sm text-slate-700 hover:bg-green-700 hover:text-white dark:text-slate-300"
                                            >
                                                {copy.postUpdate}
                                            </Link>
                                        )}
                                        {resource.permissions.can_update && (
                                            <Link
                                                href={edit.url(resource.uuid)}
                                                className="block px-4 py-2 text-sm text-slate-700 hover:bg-green-700 hover:text-white dark:text-slate-300"
                                            >
                                                {copy.edit}
                                            </Link>
                                        )}
                                        {resource.permissions.can_delete && (
                                            <Link
                                                href={deleteMethod.url(resource.uuid)}
                                                className="block px-4 py-2 text-sm text-slate-700 hover:bg-red-700 hover:text-white dark:text-slate-300"
                                            >
                                                {copy.delete}
                                            </Link>
                                        )}
                                    </div>
                                )}
                            </div>
                        )}
                    </div>
                </div>

                <div className="w-full rounded-lg bg-white p-4 shadow-md dark:bg-slate-900 dark:shadow-slate-700">
                    <div className="grid grid-rows-2 gap-4 sm:grid-cols-4 sm:grid-rows-none">
                        <div className="sm:col-span-3">
                            <div className="mb-4 text-xs text-slate-400">{resource.brief}</div>
                            <div
                                className="prose dark:prose-invert max-w-none"
                                dangerouslySetInnerHTML={{ __html: resource.description_html }}
                            />
                        </div>
                        <div className="flex text-xs text-slate-500 sm:flex-col sm:justify-center dark:text-slate-300">
                            <div className="rounded bg-slate-100 p-4 dark:bg-slate-800">
                                <div className="flex justify-between">
                                    <span>{copy.author}:</span>
                                    <Link href={resource.author.url} className="text-green-400 hover:underline">
                                        {resource.author.username}
                                    </Link>
                                </div>
                                <div className="flex justify-between">
                                    <span>{copy.rating}:</span>
                                    <span className="flex items-center gap-1">
                                        <Stars count={resource.rating.stars} />
                                        {resource.rating.average} ({resource.rating.count})
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span>{copy.downloads}:</span>
                                    <span>{resource.downloads}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>{copy.views}:</span>
                                    <span>{resource.views}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>{copy.created}:</span>
                                    <span>{resource.created_at}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>{copy.updated}:</span>
                                    <span>{resource.updated_at}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="mx-auto mt-10 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900 dark:shadow-slate-700">
                    <div className="w-full border-b px-4 py-5 sm:px-6 dark:border-slate-700">
                        <h3 className="text-lg font-medium text-slate-900 dark:text-white">{copy.updates}</h3>
                    </div>
                    <div className="flex w-full flex-col divide-y dark:divide-slate-700">
                        {resource.updates.length > 0 ? (
                            resource.updates.map((update) => (
                                <div key={update.id} className="flex w-full items-center justify-between gap-6 p-4 dark:text-white">
                                    <button
                                        type="button"
                                        className="text-left text-green-400 hover:underline"
                                        onClick={() => setActiveUpdate(update)}
                                    >
                                        {update.title}
                                    </button>
                                    <div className="flex-1 text-slate-400">{update.description_excerpt}</div>
                                    <div className="text-sm">{update.game_version}</div>
                                    <div className="text-sm">{update.created_at}</div>
                                    <div className="text-sm">
                                        {update.downloads} {copy.downloads.toLowerCase()}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="flex w-full items-center justify-center p-4">
                                <p className="text-slate-400">{copy.nothingFound}</p>
                            </div>
                        )}
                    </div>
                </div>

                <div className="mx-auto mt-10 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900 dark:shadow-slate-700">
                    <div className="w-full border-b px-4 py-5 sm:px-6 dark:border-slate-700">
                        <h3 className="text-lg font-medium text-slate-900 dark:text-white">{copy.latestReviews}</h3>
                    </div>
                    <div className="flex w-full flex-col divide-y dark:divide-slate-700">
                        {resource.reviews.length > 0 ? (
                            resource.reviews.map((review) => (
                                <div key={review.id} className="flex w-full items-center p-4">
                                    <div className="mr-4 flex h-10 w-10 flex-col items-center justify-center">
                                        {review.author.url ? (
                                            <Link href={review.author.url}>
                                                <img
                                                    alt={review.author.username ?? ''}
                                                    src={review.author.profile_photo_url}
                                                    className="mx-auto h-10 w-10 rounded-full object-cover"
                                                />
                                            </Link>
                                        ) : (
                                            <img
                                                alt=""
                                                src={review.author.profile_photo_url}
                                                className="mx-auto h-10 w-10 rounded-full object-cover"
                                            />
                                        )}
                                    </div>
                                    <div className="flex-1 pl-1">
                                        <div className="flex items-center text-sm text-slate-400 dark:text-slate-200">
                                            {review.author.url ? (
                                                <Link href={review.author.url} className="mr-2 text-green-400 hover:underline">
                                                    {review.author.username}
                                                </Link>
                                            ) : (
                                                <span className="mr-2">{review.author.username}</span>
                                            )}
                                            · <Stars count={review.rating} /> · {review.created_at}
                                        </div>
                                        <div className="py-1 font-medium dark:text-white">{review.body}</div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="flex w-full items-center justify-center p-4">
                                <p className="text-slate-400">{copy.nothingFound}</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {activeUpdate && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div className="max-h-[80vh] w-full max-w-xl overflow-y-auto rounded-lg bg-white p-6 shadow-xl dark:bg-slate-900">
                        <div className="mb-4 flex items-start justify-between gap-4">
                            <div>
                                <h3 className="text-lg font-semibold dark:text-white">{activeUpdate.title}</h3>
                                <p className="text-sm text-slate-500">
                                    {activeUpdate.game_version} · {activeUpdate.created_at}
                                </p>
                            </div>
                            <Button type="button" variant="ghost" size="sm" onClick={() => setActiveUpdate(null)}>
                                Close
                            </Button>
                        </div>
                        <div
                            className="prose dark:prose-invert mb-6 max-w-none"
                            dangerouslySetInnerHTML={{ __html: activeUpdate.description_html }}
                        />
                        <Button asChild variant="brand">
                            <a href={activeUpdate.download_url}>{copy.download}</a>
                        </Button>
                    </div>
                </div>
            )}
        </>
    );
}
