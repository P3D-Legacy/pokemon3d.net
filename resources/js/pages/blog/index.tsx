import { Head, Link } from '@inertiajs/react';

import type { Paginated, PostCard } from '@/types';

type Props = {
    posts: Paginated<PostCard>;
    copy: {
        title: string;
        subtitle: string;
        nothingToShow: string;
        published: string;
        readingTime: string;
        comments: string;
    };
};

export default function BlogIndex({ posts, copy }: Props) {
    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:pb-16 md:px-6 lg:px-8">
                <div className="pt-5 pb-10">
                    <h2 className="text-2xl leading-7 font-bold tracking-tight text-slate-800 sm:text-3xl md:text-4xl dark:text-slate-200">
                        {copy.title}
                    </h2>
                    <h4 className="mt-1 text-sm text-slate-700 dark:text-slate-400">{copy.subtitle}</h4>
                </div>

                <div className="grid grid-cols-1 gap-y-5 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                    {posts.data.map((post) => (
                        <Link
                            key={post.uuid}
                            href={post.url}
                            className="relative block overflow-hidden rounded-lg border border-slate-200 bg-white p-6 shadow-md hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800"
                        >
                            <span className={`absolute inset-x-0 bottom-0 h-2 ${post.sticky ? 'bg-red-500' : 'bg-green-600'}`} />
                            <h3 className="text-lg font-bold text-slate-900 sm:text-xl dark:text-slate-100">{post.title}</h3>
                            {post.tag && (
                                <p className="mt-1 text-xs font-medium text-slate-600">
                                    <span className="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-green-700 dark:bg-green-700/80 dark:text-green-100">
                                        {post.tag}
                                    </span>
                                </p>
                            )}
                            <p className="mt-4 text-sm text-slate-600 dark:text-slate-200">{post.excerpt}</p>
                            <dl className="mt-6 flex gap-4 text-xs text-slate-500 dark:text-slate-300">
                                <div>
                                    <dt className="font-medium">{copy.published}</dt>
                                    <dd>{post.published_for_humans}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium">{copy.readingTime}</dt>
                                    <dd>{post.reading_time}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium">{copy.comments}</dt>
                                    <dd>{post.comment_count}</dd>
                                </div>
                            </dl>
                        </Link>
                    ))}
                </div>

                {posts.data.length === 0 && (
                    <div className="w-full text-center text-xs">
                        <p className="mb-1 dark:text-slate-400">{copy.nothingToShow}...</p>
                    </div>
                )}

                {posts.links.length > 3 && (
                    <div className="mt-8 flex flex-wrap justify-center gap-2 rounded-lg bg-white p-4 dark:bg-slate-800">
                        {posts.links.map((link, index) =>
                            link.url ? (
                                <Link
                                    key={`${link.label}-${index}`}
                                    href={link.url}
                                    className={`rounded px-3 py-1 text-sm ${link.active ? 'bg-green-600 text-white' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200'}`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ) : (
                                <span
                                    key={`${link.label}-${index}`}
                                    className="rounded px-3 py-1 text-sm text-slate-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ),
                        )}
                    </div>
                )}
            </div>
        </>
    );
}
