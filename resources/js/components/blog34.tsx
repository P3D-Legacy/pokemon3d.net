import { Link } from '@inertiajs/react';
import { ArrowRightIcon } from '@phosphor-icons/react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { index as blogIndex } from '@/routes/blog';
import type { PostCard } from '@/types';

type Blog34Post = {
    id: string;
    title: string;
    summary: string;
    label: string;
    author: string;
    published: string;
    href: string;
};

interface Blog34Props {
    heading: string;
    description?: string;
    posts: PostCard[];
    readMoreLabel: string;
    goToBlogLabel: string;
    nothingToShowLabel: string;
    className?: string;
}

function toBlogPost(post: PostCard): Blog34Post {
    return {
        id: post.uuid,
        title: post.title,
        summary: post.excerpt,
        label: post.tag ?? 'News',
        author: post.author.username,
        published: post.published_for_humans,
        href: post.url,
    };
}

const Blog34 = ({
    heading,
    description,
    posts,
    readMoreLabel,
    goToBlogLabel,
    nothingToShowLabel,
    className,
}: Blog34Props) => {
    const mappedPosts = posts.map(toBlogPost);

    return (
        <section className={cn('border-b bg-background py-16 md:py-24', className)}>
            <div className="container mx-auto">
                <div className="mx-auto mb-12 max-w-2xl text-center md:mb-16">
                    <h2 className="mb-4 text-4xl font-medium tracking-tight md:text-5xl">{heading}</h2>
                    {description ? <p className="text-muted-foreground md:text-lg">{description}</p> : null}
                </div>

                {mappedPosts.length > 0 ? (
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        {mappedPosts.map((post) => (
                            <Link
                                key={post.id}
                                href={post.href}
                                className="group flex flex-col gap-4 rounded-lg border border-border p-4 transition-colors hover:bg-muted/30"
                            >
                                <div className="flex flex-1 flex-col gap-3">
                                    <Badge variant="secondary" className="w-fit font-normal">
                                        {post.label}
                                    </Badge>
                                    <h3 className="text-lg leading-snug font-medium tracking-tight">{post.title}</h3>
                                    <p className="line-clamp-3 text-sm text-muted-foreground">{post.summary}</p>
                                    <div className="mt-auto flex flex-col gap-3 border-t border-border pt-4">
                                        <div className="text-xs text-muted-foreground">
                                            <span className="font-medium text-foreground">{post.author}</span>
                                            <span className="mx-1.5">·</span>
                                            <span>{post.published}</span>
                                        </div>
                                        <span className="inline-flex items-center text-sm font-medium">
                                            Read article
                                            <ArrowRightIcon className="ml-1 size-4 transition-transform group-hover:translate-x-0.5" />
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        ))}
                    </div>
                ) : null}

                <div className="mt-8 flex flex-col items-center gap-3 text-center">
                    <Button asChild>
                        <Link href={blogIndex()}>{mappedPosts.length > 0 ? readMoreLabel : goToBlogLabel}</Link>
                    </Button>
                    {mappedPosts.length === 0 ? (
                        <p className="text-sm text-muted-foreground">{nothingToShowLabel}...</p>
                    ) : null}
                </div>
            </div>
        </section>
    );
};

export { Blog34 };
