import { Head, Link } from '@inertiajs/react';

import { index as blogIndex } from '@/routes/blog';

type Props = {
    post: {
        uuid: string;
        title: string;
        sticky: boolean;
        body_html: string;
        published_at: string;
        updated_at: string | null;
        likes: string;
        views: string;
        reading_time: string;
        comments: string;
        tags: string[];
        is_outdated: boolean;
        author: {
            username: string;
            profile_photo_url: string;
            url: string;
        };
    };
    copy: {
        likes: string;
        views: string;
        comments: string;
        updated: string;
        outdatedNote: string;
        blog: string;
    };
};

export default function BlogShow({ post, copy }: Props) {
    return (
        <>
            <Head title={post.title} />

            <div className="mx-auto max-w-3xl px-4 py-10 sm:pb-16 md:px-6 lg:px-8">
                <nav className="mb-6 text-sm text-slate-500">
                    <Link href={blogIndex()} className="hover:underline">
                        {copy.blog}
                    </Link>
                    <span className="mx-2">/</span>
                    <span className="text-slate-700 dark:text-slate-300">{post.title}</span>
                </nav>

                <article className="rounded-lg border border-slate-200 bg-white p-6 shadow-md sm:p-8 dark:border-slate-700 dark:bg-slate-900">
                    <time className="font-semibold text-slate-700 dark:text-slate-300" dateTime={post.published_at}>
                        {post.published_at}
                    </time>
                    {post.updated_at && (
                        <p className="mt-2 text-xs text-slate-500">
                            {copy.updated}: <time dateTime={post.updated_at}>{post.updated_at}</time>
                        </p>
                    )}

                    <h1 className="mt-8 text-2xl font-extrabold tracking-tight break-words text-slate-900 md:text-3xl dark:text-slate-200">
                        {post.title}
                    </h1>

                    <div className="mt-2 text-sm text-slate-700 dark:text-slate-400">
                        {post.likes} {copy.likes} &middot; {post.views} {copy.views} &middot; {post.reading_time} &middot;{' '}
                        {post.comments} {copy.comments}
                        {post.tags.length > 0 && (
                            <>
                                {' '}
                                &middot; {post.tags.join(', ')}
                            </>
                        )}
                    </div>

                    <div className="mt-8 flex items-center">
                        <img
                            src={post.author.profile_photo_url}
                            alt={post.author.username}
                            className="mr-3 h-9 w-9 rounded-full bg-slate-50 dark:bg-slate-800"
                        />
                        <div className="text-sm">
                            <div className="text-slate-900 dark:text-slate-200">{post.author.username}</div>
                            <Link href={post.author.url} className="text-green-500 hover:text-green-600">
                                @{post.author.username}
                            </Link>
                        </div>
                    </div>

                    {post.is_outdated && (
                        <div className="mt-8 rounded-md bg-blue-50 p-4 dark:bg-blue-700">
                            <p className="text-sm font-semibold text-blue-700 dark:text-blue-100">{copy.outdatedNote}</p>
                        </div>
                    )}

                    <div
                        className="prose dark:prose-invert mt-8 max-w-none"
                        dangerouslySetInnerHTML={{ __html: post.body_html }}
                    />
                </article>
            </div>
        </>
    );
}
