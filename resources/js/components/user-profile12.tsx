import { Link } from '@inertiajs/react';
import { ChatCircleIcon, PencilSimpleIcon, PlusIcon, SealCheckIcon } from '@phosphor-icons/react';
import type { ReactNode } from 'react';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { cn } from '@/lib/utils';

interface Stat {
    label: string;
    value: string;
}

interface User {
    name: string;
    username: string;
    avatar?: string;
    coverImage?: string;
    bio?: string;
    verified?: boolean;
}

interface UserProfile12Props {
    user?: User;
    stats?: Stat[];
    className?: string;
    coverClassName?: string;
    editHref?: string | null;
    showFollowActions?: boolean;
    children?: ReactNode;
}

const UserProfile12 = ({
    user = {
        name: 'Alex Morgan',
        username: '@Alex_Morgan',
        avatar: 'https://deifkwefumgah.cloudfront.net/shadcnblocks/block/avatar/avatar8.jpg',
        coverImage:
            'https://deifkwefumgah.cloudfront.net/shadcnblocks/block/photos/pawel-czerwinski-O4fAgtXLRwI-unsplash.jpg',
        bio: 'Full-stack developer passionate about building beautiful, performant web applications. Open source contributor and TypeScript enthusiast.',
        verified: true,
    },
    stats = [
        { label: 'Posts', value: '847' },
        { label: 'Following', value: '312' },
        { label: 'Followers', value: '89k' },
    ],
    className,
    coverClassName,
    editHref = null,
    showFollowActions = true,
    children,
}: UserProfile12Props) => {
    const initials = user.name
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0] ?? '')
        .join('')
        .toUpperCase()
        .slice(0, 2);

    return (
        <Card className={cn('w-full max-w-md overflow-hidden pt-0', className)}>
            <div
                className={cn('h-36 bg-muted bg-cover bg-center', coverClassName)}
                style={{
                    backgroundImage: user.coverImage ? `url(${user.coverImage})` : undefined,
                }}
            />

            <CardContent className="relative px-6 pb-6">
                <Avatar className="-mt-14 size-28 border-4 border-card shadow-lg">
                    <AvatarImage src={user.avatar} alt={user.name} className="object-cover" />
                    <AvatarFallback className="text-2xl font-semibold">{initials}</AvatarFallback>
                </Avatar>

                {editHref ? (
                    <Button variant="outline" size="sm" className="absolute top-4 right-6" asChild>
                        <Link href={editHref}>
                            <PencilSimpleIcon data-icon="inline-start" />
                            Edit Profile
                        </Link>
                    </Button>
                ) : null}

                <div className="mt-3">
                    <div className="flex items-center gap-2">
                        <h2 className="text-2xl font-bold">{user.name}</h2>
                        {user.verified ? <SealCheckIcon className="size-6 text-primary" weight="fill" /> : null}
                    </div>
                    <p className="text-muted-foreground">{user.username}</p>
                </div>

                {showFollowActions ? (
                    <div className="mt-4 flex items-center gap-2">
                        <Button>
                            <PlusIcon data-icon="inline-start" />
                            Follow
                        </Button>
                        <Button variant="outline" size="icon">
                            <ChatCircleIcon />
                        </Button>
                    </div>
                ) : null}

                {user.bio ? <p className="mt-5 text-sm text-muted-foreground">{user.bio}</p> : null}

                {stats.length > 0 ? (
                    <div className="mt-6 flex items-center justify-between border-t pt-6">
                        {stats.map((stat) => (
                            <div key={stat.label} className="text-center">
                                <div className="text-2xl font-bold">{stat.value}</div>
                                <div className="text-sm text-muted-foreground">{stat.label}</div>
                            </div>
                        ))}
                    </div>
                ) : null}

                {children}
            </CardContent>
        </Card>
    );
};

export { UserProfile12 };
export type { Stat, User, UserProfile12Props };
