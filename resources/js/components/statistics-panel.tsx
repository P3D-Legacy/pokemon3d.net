import {
    ChartBarIcon,
    ClockIcon,
    CoinsIcon,
    FootprintsIcon,
    HashIcon,
    MagnifyingGlassIcon,
    PulseIcon,
    StarIcon,
    SwordIcon,
    UsersIcon,
} from '@phosphor-icons/react';
import type { ComponentType, SVGProps } from 'react';
import { useState } from 'react';

import { Input } from '@/components/ui/input';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';

export type StatisticItem = {
    name: string;
    value: string;
};

type IconComponent = ComponentType<SVGProps<SVGSVGElement> & { weight?: 'regular' | 'fill' }>;

type StatisticsPanelProps = {
    statistics: StatisticItem[];
};

function humaniseName(name: string): string {
    return name
        .replace(/[_-]+/g, ' ')
        .replace(/([a-z])([A-Z])/g, '$1 $2')
        .replace(/\s+/g, ' ')
        .trim();
}

function formatValue(value: string): string {
    const numeric = Number(value);

    if (!Number.isNaN(numeric) && value.trim() !== '') {
        return numeric.toLocaleString();
    }

    return value || '0';
}

function iconForStat(name: string): IconComponent {
    const key = name.toLowerCase();

    if (key.includes('step') || key.includes('walk') || key.includes('run')) {
        return FootprintsIcon;
    }

    if (key.includes('battle') || key.includes('fight') || key.includes('win') || key.includes('loss')) {
        return SwordIcon;
    }

    if (key.includes('time') || key.includes('hour') || key.includes('play')) {
        return ClockIcon;
    }

    if (key.includes('money') || key.includes('coin') || key.includes('spent') || key.includes('earned')) {
        return CoinsIcon;
    }

    if (key.includes('star') || key.includes('point') || key.includes('score')) {
        return StarIcon;
    }

    if (key.includes('trade') || key.includes('friend') || key.includes('player')) {
        return UsersIcon;
    }

    if (key.includes('health') || key.includes('damage') || key.includes('hp')) {
        return PulseIcon;
    }

    if (key.includes('count') || key.includes('total') || key.includes('number')) {
        return HashIcon;
    }

    return ChartBarIcon;
}

export function StatisticsPanel({ statistics }: StatisticsPanelProps) {
    const { t } = useTranslations();
    const [query, setQuery] = useState('');

    const filtered = statistics.filter((stat) => {
        if (!query.trim()) {
            return true;
        }

        const haystack = `${stat.name} ${humaniseName(stat.name)} ${stat.value}`.toLowerCase();

        return haystack.includes(query.trim().toLowerCase());
    });

    if (statistics.length === 0) {
        return <p className="text-sm text-muted-foreground">{t('No statistics available')}</p>;
    }

    return (
        <div className="flex w-full min-w-0 flex-col gap-4">
            <div className="flex flex-wrap items-end justify-between gap-3">
                <div className="flex flex-col gap-1">
                    <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                        <ChartBarIcon className="size-4" weight="fill" />
                        {t('Tracked')}
                    </div>
                    <div className="text-2xl font-bold">{statistics.length}</div>
                </div>

                <div className="relative w-full max-w-xs min-w-0">
                    <MagnifyingGlassIcon className="pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        value={query}
                        onChange={(event) => setQuery(event.target.value)}
                        placeholder={t('Search statistics')}
                        className="pl-8"
                        aria-label={t('Search statistics')}
                    />
                </div>
            </div>

            {filtered.length === 0 ? (
                <div className="flex items-center gap-2 text-sm text-muted-foreground">
                    <MagnifyingGlassIcon className="size-4" />
                    {t('No statistics match this search')}
                </div>
            ) : (
                <div className="grid w-full min-w-0 grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    {filtered.map((stat, index) => {
                        const Icon = iconForStat(stat.name);
                        const label = humaniseName(stat.name) || t('Statistic');

                        return (
                            <div
                                key={`${stat.name}-${index}`}
                                className={cn('flex min-w-0 gap-3 border border-border bg-muted/20 p-3')}
                            >
                                <div className="flex size-10 shrink-0 items-center justify-center border border-border bg-background">
                                    <Icon className="size-5 text-muted-foreground" weight="fill" />
                                </div>
                                <div className="flex min-w-0 flex-1 flex-col gap-1">
                                    <div className="truncate text-xs text-muted-foreground" title={label}>
                                        {label}
                                    </div>
                                    <div className="truncate text-lg font-semibold tabular-nums">{formatValue(stat.value)}</div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
