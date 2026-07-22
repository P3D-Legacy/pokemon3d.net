import { Head, Link } from '@inertiajs/react';
import { NewspaperIcon } from '@phosphor-icons/react';

import { BlogPostCard } from '@/components/blog-post-card';
import { Button } from '@/components/ui/button';
import { cn, paginationLabel } from '@/lib/utils';
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

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-2">
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <NewspaperIcon className="size-5" weight="fill" />
                        <span className="text-sm">News</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                    <p className="max-w-2xl text-sm text-muted-foreground">{copy.subtitle}</p>
                </div>

                {posts.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <NewspaperIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">{copy.nothingToShow}</div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        {posts.data.map((post) => (
                            <BlogPostCard
                                key={post.uuid}
                                post={post}
                                publishedLabel={copy.published}
                                commentsLabel={copy.comments.toLowerCase()}
                            />
                        ))}
                    </div>
                )}

                {posts.links.length > 3 ? (
                    <div className="mt-8 flex flex-wrap items-center justify-center gap-2">
                        {posts.links.map((link, index) =>
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
