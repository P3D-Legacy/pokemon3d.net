import { Link } from '@inertiajs/react';
import { NewspaperIcon } from '@phosphor-icons/react';

import { BlogPostCard } from '@/components/blog-post-card';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { index as blogIndex } from '@/routes/blog';
import type { PostCard } from '@/types';

interface Blog34Props {
    heading: string;
    description?: string;
    posts: PostCard[];
    readMoreLabel: string;
    goToBlogLabel: string;
    nothingToShowLabel: string;
    className?: string;
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
    return (
        <section className={cn('border-b bg-background py-16 md:py-24', className)}>
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">

                {posts.length > 0 ? (
                    <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        {posts.map((post) => (
                            <BlogPostCard key={post.uuid} post={post} />
                        ))}
                    </div>
                ) : (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <NewspaperIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">{nothingToShowLabel}</div>
                    </div>
                )}

                <div className="mt-8 flex justify-center">
                    <Button asChild>
                        <Link href={blogIndex.url()}>{posts.length > 0 ? readMoreLabel : goToBlogLabel}</Link>
                    </Button>
                </div>
            </div>
        </section>
    );
};

export { Blog34 };
