import {
    CirclesFourIcon,
    LockSimpleIcon,
    MagnifyingGlassIcon,
    MedalIcon,
    TrophyIcon,
} from '@phosphor-icons/react';
import type { ComponentType, SVGProps } from 'react';
import { useState } from 'react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';

export type TrophyItem = {
    id: number;
    title: string;
    difficulty?: string | null;
    description?: string | null;
    image_url?: string | null;
    achieved: boolean;
};

type TrophyFilter = 'all' | 'achieved' | 'locked';
type TrophyDifficulty = 'bronze' | 'silver' | 'gold' | 'platinum';
type IconComponent = ComponentType<SVGProps<SVGSVGElement> & { weight?: 'regular' | 'fill' }>;

type TrophiesPanelProps = {
    trophies: TrophyItem[];
    achievedCount: number;
    totalCount: number;
};

const filterDefs: Array<{ key: TrophyFilter; labelKey: string; icon: IconComponent }> = [
    { key: 'all', labelKey: 'All', icon: CirclesFourIcon },
    { key: 'achieved', labelKey: 'Achieved', icon: TrophyIcon },
    { key: 'locked', labelKey: 'Locked', icon: LockSimpleIcon },
];

const difficultyStyles: Record<TrophyDifficulty, string> = {
    bronze: 'border-orange-900/40 bg-orange-950/10 text-orange-950 dark:border-orange-800/70 dark:bg-orange-950 dark:text-orange-300',
    silver: 'border-slate-400/70 bg-slate-200 text-slate-800 dark:border-slate-400/50 dark:bg-slate-700 dark:text-slate-100',
    gold: 'border-yellow-500/70 bg-yellow-300/40 text-yellow-950 dark:border-yellow-400/70 dark:bg-yellow-500/20 dark:text-yellow-200',
    platinum: 'border-cyan-500/60 bg-cyan-100 text-cyan-950 dark:border-cyan-400/50 dark:bg-cyan-950 dark:text-cyan-100',
};

function normalisedDifficulty(difficulty?: string | null): TrophyDifficulty | null {
    const value = difficulty?.trim().toLowerCase();

    if (value === 'bronze' || value === 'silver' || value === 'gold' || value === 'platinum') {
        return value;
    }

    return null;
}

function difficultyClassName(difficulty?: string | null): string {
    const key = normalisedDifficulty(difficulty);

    if (!key) {
        return 'border-border bg-muted text-muted-foreground';
    }

    return difficultyStyles[key];
}

export function TrophiesPanel({ trophies, achievedCount, totalCount }: TrophiesPanelProps) {
    const { t } = useTranslations();
    const [filter, setFilter] = useState<TrophyFilter>('all');
    const progress = totalCount > 0 ? Math.min(100, Math.round((achievedCount / totalCount) * 100)) : 0;

    const filtered = trophies.filter((trophy) => {
        if (filter === 'achieved') {
            return trophy.achieved;
        }

        if (filter === 'locked') {
            return !trophy.achieved;
        }

        return true;
    });

    return (
        <div className="flex w-full min-w-0 flex-col gap-4">
            <div className="flex flex-col gap-3">
                <div className="flex flex-wrap items-end justify-between gap-3">
                    <div className="flex flex-wrap gap-4 text-sm">
                        <div className="flex flex-col gap-1">
                            <div className="flex items-center gap-1.5 text-muted-foreground">
                                <TrophyIcon className="size-4" weight="fill" />
                                {t('Achieved')}
                            </div>
                            <div className="text-2xl font-bold">{achievedCount}</div>
                        </div>
                        <div className="flex flex-col gap-1">
                            <div className="flex items-center gap-1.5 text-muted-foreground">
                                <CirclesFourIcon className="size-4" weight="fill" />
                                {t('Total')}
                            </div>
                            <div className="text-2xl font-bold">{totalCount}</div>
                        </div>
                    </div>
                    <div className="text-sm text-muted-foreground">{t(':progress% complete', { progress })}</div>
                </div>
                <div className="h-2 overflow-hidden bg-muted">
                    <div className="h-full bg-primary transition-all" style={{ width: `${progress}%` }} />
                </div>
            </div>

            <div className="flex flex-wrap gap-2">
                {filterDefs.map((item) => {
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
                            {t(item.labelKey)}
                        </Button>
                    );
                })}
            </div>

            {filtered.length === 0 ? (
                <div className="flex items-center gap-2 text-sm text-muted-foreground">
                    <MagnifyingGlassIcon className="size-4" />
                    {t('No trophies match this filter')}
                </div>
            ) : (
                <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    {filtered.map((trophy) => (
                        <div
                            key={trophy.id}
                            className={cn(
                                'flex gap-3 border p-3',
                                trophy.achieved ? 'border-primary bg-primary/10' : 'border-border/60 bg-muted/20',
                            )}
                        >
                            <div
                                className={cn(
                                    'flex size-16 shrink-0 items-center justify-center overflow-hidden border bg-muted',
                                    !trophy.achieved && 'opacity-50 grayscale',
                                )}
                            >
                                {trophy.image_url ? (
                                    <img
                                        src={trophy.image_url}
                                        alt={trophy.title}
                                        className="size-full object-cover"
                                        loading="lazy"
                                    />
                                ) : (
                                    <TrophyIcon className="size-6 text-muted-foreground" weight="fill" />
                                )}
                            </div>

                            <div className="flex min-w-0 flex-1 flex-col gap-2">
                                <div className="flex flex-wrap items-start justify-between gap-2">
                                    <div className="min-w-0 font-medium">{trophy.title}</div>
                                    <Badge variant={trophy.achieved ? 'default' : 'outline'}>
                                        {trophy.achieved ? (
                                            <TrophyIcon data-icon="inline-start" weight="fill" />
                                        ) : (
                                            <LockSimpleIcon data-icon="inline-start" weight="fill" />
                                        )}
                                        {trophy.achieved ? t('Achieved') : t('Locked')}
                                    </Badge>
                                </div>

                                {trophy.difficulty ? (
                                    <Badge
                                        variant="outline"
                                        className={cn('w-fit border', difficultyClassName(trophy.difficulty))}
                                    >
                                        <MedalIcon data-icon="inline-start" weight="fill" />
                                        {trophy.difficulty}
                                    </Badge>
                                ) : null}

                                {trophy.description ? (
                                    <p className="text-sm text-muted-foreground">{trophy.description}</p>
                                ) : null}
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}
