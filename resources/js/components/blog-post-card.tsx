import { Link } from '@inertiajs/react';
import { CaretRightIcon, ChatCircleIcon, ClockIcon, PushPinIcon } from '@phosphor-icons/react';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import type { PostCard } from '@/types';

type Props = {
    post: PostCard;
    publishedLabel?: string;
    commentsLabel?: string;
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

export function BlogPostCard({
    post,
    publishedLabel = 'Published',
    commentsLabel = 'comments',
}: Props) {
    return (
        <Link
            href={post.url}
            className="group flex min-w-0 flex-col gap-4 border border-border bg-card p-5 transition-colors hover:border-primary hover:bg-primary/5"
        >
            <div className="flex flex-wrap items-center gap-2">
                {post.sticky ? (
                    <Badge variant="default">
                        <PushPinIcon data-icon="inline-start" weight="fill" />
                        Sticky
                    </Badge>
                ) : null}
                {post.tag ? <Badge variant="secondary">{post.tag}</Badge> : null}
            </div>

            <div className="flex min-w-0 flex-1 flex-col gap-2">
                <h2 className="text-lg font-semibold tracking-tight group-hover:text-primary">{post.title}</h2>
                <p className="line-clamp-3 text-sm leading-relaxed text-muted-foreground">{post.excerpt}</p>
            </div>

            <div className="mt-auto flex flex-col gap-3 border-t border-border pt-4">
                <div className="flex items-center gap-2">
                    <Avatar className="size-8">
                        <AvatarImage src={post.author.profile_photo_url} alt={post.author.username} />
                        <AvatarFallback>{initials(post.author.username)}</AvatarFallback>
                    </Avatar>
                    <span className="truncate text-sm font-medium">{post.author.username}</span>
                    <CaretRightIcon className="ml-auto size-4 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-0.5 group-hover:text-primary" />
                </div>

                <div className="flex flex-wrap gap-x-4 gap-y-1 text-xs text-muted-foreground">
                    <span>
                        {publishedLabel} {post.published_for_humans}
                    </span>
                    <span className="inline-flex items-center gap-1">
                        <ClockIcon className="size-3.5" weight="fill" />
                        {post.reading_time}
                    </span>
                    <span className="inline-flex items-center gap-1">
                        <ChatCircleIcon className="size-3.5" weight="fill" />
                        {post.comment_count} {commentsLabel}
                    </span>
                </div>
            </div>
        </Link>
    );
}
