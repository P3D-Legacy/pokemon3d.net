import {
    CheckCircleIcon,
    CirclesFourIcon,
    EyeIcon,
    EyeSlashIcon,
    MagnifyingGlassIcon,
} from '@phosphor-icons/react';
import type { ComponentType, SVGProps } from 'react';
import { useState } from 'react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

export type PokedexEntry = {
    id: string;
    name: string;
    seen: boolean;
    caught: boolean;
};

export type PokedexDefinition = {
    slug: string;
    name: string;
    caught_count: number;
    seen_count: number;
    entries: PokedexEntry[];
};

type PokedexFilter = 'all' | 'caught' | 'seen';
type EntryStatus = 'caught' | 'seen' | 'unseen';
type IconComponent = ComponentType<SVGProps<SVGSVGElement> & { weight?: 'regular' | 'fill' }>;

type PokedexPanelProps = {
    pokedexes: PokedexDefinition[];
};

const filters: Array<{ key: PokedexFilter; label: string; icon: IconComponent }> = [
    { key: 'all', label: 'All', icon: CirclesFourIcon },
    { key: 'caught', label: 'Caught', icon: CheckCircleIcon },
    { key: 'seen', label: 'Seen', icon: EyeIcon },
];

const statusIcons: Record<EntryStatus, IconComponent> = {
    caught: CheckCircleIcon,
    seen: EyeIcon,
    unseen: EyeSlashIcon,
};

function formatDexNumber(id: string): string {
    if (id.includes('_') || id.includes(';')) {
        return `#${id}`;
    }

    const numericId = Number.parseInt(id, 10);

    if (Number.isNaN(numericId)) {
        return `#${id}`;
    }

    return `#${String(numericId).padStart(3, '0')}`;
}

function entryStatus(entry: PokedexEntry): EntryStatus {
    if (entry.caught) {
        return 'caught';
    }

    if (entry.seen) {
        return 'seen';
    }

    return 'unseen';
}

function statusBadgeVariant(status: EntryStatus): 'default' | 'secondary' | 'outline' {
    if (status === 'caught') {
        return 'default';
    }

    if (status === 'seen') {
        return 'secondary';
    }

    return 'outline';
}

function statusLabel(status: EntryStatus): string {
    if (status === 'caught') {
        return 'Caught';
    }

    if (status === 'seen') {
        return 'Seen';
    }

    return 'Unseen';
}

function defaultDexSlug(pokedexes: PokedexDefinition[]): string {
    const national = pokedexes.find((dex) => dex.slug === 'pokedex_national');

    return national?.slug ?? pokedexes[0]?.slug ?? '';
}

export function PokedexPanel({ pokedexes }: PokedexPanelProps) {
    const [activeDex, setActiveDex] = useState(() => defaultDexSlug(pokedexes));
    const [filter, setFilter] = useState<PokedexFilter>('all');

    const selectedDex = pokedexes.find((dex) => dex.slug === activeDex) ?? pokedexes[0] ?? null;

    const caughtCount = selectedDex?.caught_count ?? 0;
    const seenCount = selectedDex?.seen_count ?? 0;
    const progress = seenCount > 0 ? Math.min(100, Math.round((caughtCount / seenCount) * 100)) : 0;

    const filtered = (selectedDex?.entries ?? []).filter((entry) => {
        if (filter === 'caught') {
            return entry.caught;
        }

        if (filter === 'seen') {
            return entry.seen && !entry.caught;
        }

        return true;
    });

    if (pokedexes.length === 0) {
        return (
            <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <MagnifyingGlassIcon className="size-4" />
                No Pokédex definitions available yet
            </div>
        );
    }

    return (
        <div className="flex w-full min-w-0 flex-col gap-4">
            <div className="flex flex-wrap gap-3 border-b border-border text-sm">
                {pokedexes.map((dex) => (
                    <button
                        key={dex.slug}
                        type="button"
                        className={cn(
                            'pb-2',
                            (selectedDex?.slug ?? '') === dex.slug
                                ? 'border-b-2 border-primary text-primary'
                                : 'text-muted-foreground',
                        )}
                        onClick={() => {
                            setActiveDex(dex.slug);
                            setFilter('all');
                        }}
                    >
                        {dex.name}
                    </button>
                ))}
            </div>

            <div className="flex flex-col gap-3">
                <div className="flex flex-wrap items-end justify-between gap-3">
                    <div className="flex flex-wrap gap-4 text-sm">
                        <div className="flex flex-col gap-1">
                            <div className="flex items-center gap-1.5 text-muted-foreground">
                                <CheckCircleIcon className="size-4" weight="fill" />
                                Caught
                            </div>
                            <div className="text-2xl font-bold">{caughtCount}</div>
                        </div>
                        <div className="flex flex-col gap-1">
                            <div className="flex items-center gap-1.5 text-muted-foreground">
                                <EyeIcon className="size-4" weight="fill" />
                                Seen
                            </div>
                            <div className="text-2xl font-bold">{seenCount}</div>
                        </div>
                    </div>
                    <div className="text-sm text-muted-foreground">{progress}% caught of seen</div>
                </div>
                <div className="h-2 overflow-hidden bg-muted">
                    <div className="h-full bg-primary transition-all" style={{ width: `${progress}%` }} />
                </div>
            </div>

            <div className="flex flex-wrap gap-2">
                {filters.map((item) => {
                    const Icon = item.icon;

                    return (
                        <Button
                            key={item.key}
                            type="button"
                            size="sm"
                            variant={filter === item.key ? 'default' : 'outline'}
                            onClick={() => setFilter(item.key)}
                        >
                            <Icon data-icon="inline-start" weight={filter === item.key ? 'fill' : 'regular'} />
                            {item.label}
                        </Button>
                    );
                })}
            </div>

            {filtered.length === 0 ? (
                <div className="flex items-center gap-2 text-sm text-muted-foreground">
                    <MagnifyingGlassIcon className="size-4" />
                    No Pokémon match this filter
                </div>
            ) : (
                <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    {filtered.map((entry) => {
                        const status = entryStatus(entry);
                        const StatusIcon = statusIcons[status];

                        return (
                            <div
                                key={entry.id}
                                className={cn(
                                    'flex flex-col gap-2 border p-3',
                                    status === 'caught' && 'border-primary bg-primary/10',
                                    status === 'seen' && 'border-border bg-muted/40',
                                    status === 'unseen' && 'border-border/60 bg-muted/20 text-muted-foreground',
                                )}
                            >
                                <div className="text-xs text-muted-foreground">{formatDexNumber(entry.id)}</div>
                                <div className="truncate font-medium">{entry.name}</div>
                                <Badge variant={statusBadgeVariant(status)}>
                                    <StatusIcon data-icon="inline-start" weight="fill" />
                                    {statusLabel(status)}
                                </Badge>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
