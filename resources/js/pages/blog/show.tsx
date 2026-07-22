import { Head, Link } from '@inertiajs/react';
import {
    ArrowLeftIcon,
    ChatCircleIcon,
    ClockIcon,
    EyeIcon,
    HeartIcon,
    InfoIcon,
    NewspaperIcon,
    PushPinIcon,
} from '@phosphor-icons/react';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
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

function initials(name: string): string {
    return name
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0] ?? '')
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

export default function BlogShow({ post, copy }: Props) {
    return (
        <>
            <Head title={post.title} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={blogIndex.url()}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            Back to {copy.blog.toLowerCase()}
                        </Link>
                    </Button>

                    <div className="flex flex-col gap-3">
                        <div className="flex flex-wrap items-center gap-2">
                            <div className="flex items-center gap-2 text-muted-foreground">
                                <NewspaperIcon className="size-5" weight="fill" />
                                <span className="text-sm">{copy.blog}</span>
                            </div>
                            {post.sticky ? (
                                <Badge variant="default">
                                    <PushPinIcon data-icon="inline-start" weight="fill" />
                                    Sticky
                                </Badge>
                            ) : null}
                            {post.tags.map((tag) => (
                                <Badge key={tag} variant="secondary">
                                    {tag}
                                </Badge>
                            ))}
                        </div>

                        <h1 className="text-3xl font-semibold tracking-tight break-words md:text-4xl">
                            {post.title}
                        </h1>

                        <div className="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-muted-foreground">
                            <time dateTime={post.published_at}>{post.published_at}</time>
                            {post.updated_at ? (
                                <span>
                                    {copy.updated}{' '}
                                    <time dateTime={post.updated_at}>{post.updated_at}</time>
                                </span>
                            ) : null}
                            <span className="inline-flex items-center gap-1.5">
                                <HeartIcon className="size-3.5" weight="fill" />
                                {post.likes} {copy.likes.toLowerCase()}
                            </span>
                            <span className="inline-flex items-center gap-1.5">
                                <EyeIcon className="size-3.5" weight="fill" />
                                {post.views} {copy.views.toLowerCase()}
                            </span>
                            <span className="inline-flex items-center gap-1.5">
                                <ClockIcon className="size-3.5" weight="fill" />
                                {post.reading_time}
                            </span>
                            <span className="inline-flex items-center gap-1.5">
                                <ChatCircleIcon className="size-3.5" weight="fill" />
                                {post.comments} {copy.comments.toLowerCase()}
                            </span>
                        </div>

                        <div className="flex items-center gap-3 pt-1">
                            <Avatar className="size-10">
                                <AvatarImage src={post.author.profile_photo_url} alt={post.author.username} />
                                <AvatarFallback>{initials(post.author.username)}</AvatarFallback>
                            </Avatar>
                            <div className="min-w-0">
                                <div className="text-sm font-medium">{post.author.username}</div>
                                <Link href={post.author.url} className="text-sm text-primary hover:underline">
                                    @{post.author.username}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                {post.is_outdated ? (
                    <div className="mb-6 flex gap-3 border border-border bg-muted/30 px-4 py-3">
                        <InfoIcon className="mt-0.5 size-5 shrink-0 text-muted-foreground" weight="fill" />
                        <p className="text-sm text-muted-foreground">{copy.outdatedNote}</p>
                    </div>
                ) : null}

                <Card>
                    <CardContent className="pt-(--card-spacing)">
                        <article
                            className="prose dark:prose-invert max-w-none"
                            dangerouslySetInnerHTML={{ __html: post.body_html }}
                        />
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
