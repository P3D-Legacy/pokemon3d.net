import { Head, Link } from '@inertiajs/react';
import {
    CalendarBlankIcon,
    CaretRightIcon,
    ClockIcon,
    GameControllerIcon,
    HardDrivesIcon,
    MapPinIcon,
    UsersIcon,
} from '@phosphor-icons/react';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import type { Paginated } from '@/types';

type Member = {
    id: number;
    username: string;
    name: string | null;
    profile_photo_url: string;
    location: string | null;
    joined: string | null;
    last_online: string | null;
    has_game_save: boolean;
    has_gamejolt: boolean;
    url: string;
};

type Props = {
    members: Paginated<Member>;
};

function initials(name: string | null, username: string): string {
    const source = name?.trim() || username;

    return source
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0] ?? '')
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

function paginationLabel(label: string): string {
    return label
        .replace(/&laquo;/g, '«')
        .replace(/&raquo;/g, '»')
        .replace(/&nbsp;/g, ' ')
        .replace(/<[^>]*>/g, '')
        .trim();
}

export default function MembersIndex({ members }: Props) {
    const total = members.total ?? members.meta?.total ?? members.data.length;

    return (
        <>
            <Head title="Members" />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-wrap items-end justify-between gap-4">
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <UsersIcon className="size-5" weight="fill" />
                            <span className="text-sm">Community</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">Members</h1>
                        <p className="text-sm text-muted-foreground">Browse verified trainers on Pokémon 3D.</p>
                    </div>
                    <div className="text-sm text-muted-foreground">
                        <span className="text-2xl font-bold text-foreground">{total}</span>
                        <span className="ml-2">{total === 1 ? 'member' : 'members'}</span>
                    </div>
                </div>

                {members.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <UsersIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">No members found</div>
                        <p className="max-w-md text-sm text-muted-foreground">
                            There are no verified members to show yet.
                        </p>
                    </div>
                ) : (
                    <div className="grid w-full min-w-0 grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        {members.data.map((member) => (
                            <Link
                                key={member.id}
                                href={member.url}
                                className="group flex min-w-0 flex-col gap-3 border border-border bg-card p-4 transition-colors hover:border-primary hover:bg-primary/5"
                            >
                                <div className="flex min-w-0 items-center gap-3">
                                    <Avatar className="size-12">
                                        <AvatarImage src={member.profile_photo_url} alt={member.username} />
                                        <AvatarFallback>{initials(member.name, member.username)}</AvatarFallback>
                                    </Avatar>
                                    <div className="min-w-0 flex-1">
                                        <div className="truncate font-medium">{member.username}</div>
                                        {member.name ? (
                                            <div className="truncate text-sm text-muted-foreground">{member.name}</div>
                                        ) : null}
                                    </div>
                                    <CaretRightIcon className="size-4 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-0.5 group-hover:text-primary" />
                                </div>

                                <div className="flex min-w-0 flex-col gap-1.5 text-xs text-muted-foreground">
                                    {member.location ? (
                                        <div className="flex min-w-0 items-center gap-1.5">
                                            <MapPinIcon className="size-3.5 shrink-0" weight="fill" />
                                            <span className="truncate">{member.location}</span>
                                        </div>
                                    ) : null}
                                    {member.last_online ? (
                                        <div className="flex min-w-0 items-center gap-1.5">
                                            <ClockIcon className="size-3.5 shrink-0" weight="fill" />
                                            <span className="truncate">Last online {member.last_online}</span>
                                        </div>
                                    ) : null}
                                    {member.joined ? (
                                        <div className="flex min-w-0 items-center gap-1.5">
                                            <CalendarBlankIcon className="size-3.5 shrink-0" weight="fill" />
                                            <span className="truncate">Joined {member.joined}</span>
                                        </div>
                                    ) : null}
                                </div>

                                {member.has_game_save || member.has_gamejolt ? (
                                    <div className="flex flex-wrap gap-1.5">
                                        {member.has_gamejolt ? (
                                            <Badge variant="secondary">
                                                <GameControllerIcon data-icon="inline-start" weight="fill" />
                                                Game Jolt
                                            </Badge>
                                        ) : null}
                                        {member.has_game_save ? (
                                            <Badge variant="outline">
                                                <HardDrivesIcon data-icon="inline-start" weight="fill" />
                                                Game Save
                                            </Badge>
                                        ) : null}
                                    </div>
                                ) : null}
                            </Link>
                        ))}
                    </div>
                )}

                {members.links.length > 3 ? (
                    <div className="mt-8 flex flex-wrap items-center justify-center gap-2">
                        {members.links.map((link, index) =>
                            link.url ? (
                                <Button
                                    key={`${link.label}-${index}`}
                                    variant={link.active ? 'default' : 'outline'}
                                    size="sm"
                                    asChild
                                >
                                    <Link href={link.url} className={cn(!link.active && 'text-muted-foreground')}>
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
