import { Head, Link, router } from '@inertiajs/react';
import {
    CalendarBlankIcon,
    CaretRightIcon,
    ClockIcon,
    GameControllerIcon,
    HardDrivesIcon,
    MagnifyingGlassIcon,
    MapPinIcon,
    UsersIcon,
    XIcon,
} from '@phosphor-icons/react';
import { useEffect, useMemo, useState } from 'react';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useTranslations } from '@/hooks/use-translations';
import { cn, paginationLabel } from '@/lib/utils';
import { index as memberIndex } from '@/routes/member';
import type { Paginated } from '@/types';

type Member = {
    id: number;
    username: string;
    name: string | null;
    profile_photo_url: string;
    location: string | null;
    joined: string | null;
    joined_for_humans: string | null;
    last_online: string | null;
    has_game_save: boolean;
    has_gamejolt: boolean;
    url: string;
};

type MemberFilters = {
    search: string;
    sort: 'last_active' | 'joined' | 'joined_oldest' | 'username' | 'username_desc';
    gamejolt: boolean;
    gamesave: boolean;
};

type Props = {
    members: Paginated<Member>;
    filters: MemberFilters;
};

type FilterQuery = {
    search?: string;
    sort?: MemberFilters['sort'];
    gamejolt?: number;
    gamesave?: number;
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

function buildQuery(filters: MemberFilters): FilterQuery {
    const query: FilterQuery = {};

    if (filters.search.trim() !== '') {
        query.search = filters.search.trim();
    }

    if (filters.sort !== 'last_active') {
        query.sort = filters.sort;
    }

    if (filters.gamejolt) {
        query.gamejolt = 1;
    }

    if (filters.gamesave) {
        query.gamesave = 1;
    }

    return query;
}

function visitMembers(nextFilters: MemberFilters, replace = false): void {
    router.get(
        memberIndex.url({ query: buildQuery(nextFilters) }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace,
        },
    );
}

export default function MembersIndex({ members, filters }: Props) {
    const { t } = useTranslations();
    const total = members.total ?? members.meta?.total ?? members.data.length;
    const [search, setSearch] = useState(filters.search);
    const hasActiveFilters =
        search.trim() !== '' || filters.sort !== 'last_active' || filters.gamejolt || filters.gamesave;

    const currentFilters = useMemo(
        (): MemberFilters => ({
            ...filters,
            search: search.trim(),
        }),
        [filters, search],
    );

    useEffect(() => {
        setSearch(filters.search);
    }, [filters.search]);

    useEffect(() => {
        const trimmed = search.trim();

        if (trimmed === filters.search.trim()) {
            return;
        }

        const timeout = window.setTimeout(() => {
            visitMembers({ ...filters, search: trimmed }, true);
        }, 300);

        return () => window.clearTimeout(timeout);
    }, [search, filters]);

    const sortOptions = useMemo(
        () =>
            [
                { value: 'last_active', label: t('Last active') },
                { value: 'joined', label: t('Newest joined') },
                { value: 'joined_oldest', label: t('Oldest joined') },
                { value: 'username', label: t('Username A–Z') },
                { value: 'username_desc', label: t('Username Z–A') },
            ] as const,
        [t],
    );

    return (
        <>
            <Head title={t('Members')} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-wrap items-end justify-between gap-4">
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <UsersIcon className="size-5" weight="fill" />
                            <span className="text-sm">{t('Community')}</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{t('Members')}</h1>
                        <p className="text-sm text-muted-foreground">
                            {t('Browse verified trainers on Pokémon 3D.')}
                        </p>
                    </div>
                    <div className="text-sm text-muted-foreground">
                        <span className="text-2xl font-bold text-foreground">{total}</span>
                        <span className="ml-2">{total === 1 ? t('member') : t('members')}</span>
                    </div>
                </div>

                <div className="mb-6 flex flex-col gap-3">
                    <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div className="relative min-w-0 flex-1">
                            <MagnifyingGlassIcon
                                className="pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2 text-muted-foreground"
                                weight="bold"
                            />
                            <Input
                                type="search"
                                value={search}
                                onChange={(event) => setSearch(event.target.value)}
                                placeholder={t('Search members')}
                                aria-label={t('Search members')}
                                className="pl-8"
                            />
                        </div>
                        <label className="flex shrink-0 items-center gap-2 text-sm text-muted-foreground">
                            <span className="sr-only sm:not-sr-only">{t('Sort by')}</span>
                            <select
                                value={filters.sort}
                                onChange={(event) =>
                                    visitMembers({
                                        ...currentFilters,
                                        sort: event.target.value as MemberFilters['sort'],
                                    })
                                }
                                className="h-8 border border-input bg-transparent px-2.5 text-xs text-foreground outline-none focus-visible:border-ring focus-visible:ring-1 focus-visible:ring-ring/50 dark:bg-input/30"
                                aria-label={t('Sort by')}
                            >
                                {sortOptions.map((option) => (
                                    <option key={option.value} value={option.value}>
                                        {option.label}
                                    </option>
                                ))}
                            </select>
                        </label>
                    </div>

                    <div className="flex flex-wrap items-center gap-2">
                        <Button
                            type="button"
                            size="sm"
                            variant={filters.gamejolt ? 'default' : 'outline'}
                            aria-pressed={filters.gamejolt}
                            onClick={() => visitMembers({ ...currentFilters, gamejolt: ! filters.gamejolt })}
                        >
                            <GameControllerIcon data-icon="inline-start" weight="fill" />
                            {t('Game Jolt')}
                        </Button>
                        <Button
                            type="button"
                            size="sm"
                            variant={filters.gamesave ? 'default' : 'outline'}
                            aria-pressed={filters.gamesave}
                            onClick={() => visitMembers({ ...currentFilters, gamesave: ! filters.gamesave })}
                        >
                            <HardDrivesIcon data-icon="inline-start" weight="fill" />
                            {t('Game Save')}
                        </Button>
                        {hasActiveFilters ? (
                            <Button
                                type="button"
                                size="sm"
                                variant="ghost"
                                className="text-muted-foreground"
                                onClick={() => {
                                    setSearch('');
                                    visitMembers({
                                        search: '',
                                        sort: 'last_active',
                                        gamejolt: false,
                                        gamesave: false,
                                    });
                                }}
                            >
                                <XIcon data-icon="inline-start" weight="bold" />
                                {t('Clear filters')}
                            </Button>
                        ) : null}
                    </div>
                </div>

                {members.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <UsersIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">{t('No members found')}</div>
                        <p className="max-w-md text-sm text-muted-foreground">
                            {hasActiveFilters
                                ? t('No members match your search')
                                : t('There are no verified members to show yet.')}
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
                                            <span className="truncate">
                                                {t('Last online')} {member.last_online}
                                            </span>
                                        </div>
                                    ) : null}
                                    {member.joined || member.joined_for_humans ? (
                                        <div className="flex min-w-0 items-center gap-1.5">
                                            <CalendarBlankIcon className="size-3.5 shrink-0" weight="fill" />
                                            <span className="truncate">
                                                {t('Joined')} {member.joined_for_humans ?? member.joined}
                                            </span>
                                        </div>
                                    ) : null}
                                </div>

                                {member.has_game_save || member.has_gamejolt ? (
                                    <div className="flex flex-wrap gap-1.5">
                                        {member.has_gamejolt ? (
                                            <Badge variant="secondary">
                                                <GameControllerIcon data-icon="inline-start" weight="fill" />
                                                {t('Game Jolt')}
                                            </Badge>
                                        ) : null}
                                        {member.has_game_save ? (
                                            <Badge variant="outline">
                                                <HardDrivesIcon data-icon="inline-start" weight="fill" />
                                                {t('Game Save')}
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
                                    <Link href={link.url} className={cn(! link.active && 'text-muted-foreground')}>
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
