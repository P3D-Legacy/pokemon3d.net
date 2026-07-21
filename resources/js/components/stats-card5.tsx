import {
    ChatCircleIcon,
    DiscordLogoIcon,
    LeafIcon,
    StarIcon,
    UserCircleIcon,
    UsersIcon,
} from '@phosphor-icons/react';
import type { ComponentType, SVGProps } from 'react';

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import type { HomeStat } from '@/types';

type IconComponent = ComponentType<SVGProps<SVGSVGElement>>;

const iconMap: Record<string, IconComponent> = {
    reviews: StarIcon,
    season: LeafIcon,
    players: UsersIcon,
    users: UserCircleIcon,
    discord: DiscordLogoIcon,
    forum: ChatCircleIcon,
};

interface StatsCard5Props {
    title: string;
    value: string;
    subtitle?: string | null;
    iconKey?: string;
    className?: string;
}

const StatsCard5 = ({ title, value, subtitle, iconKey, className }: StatsCard5Props) => {
    const Icon = (iconKey && iconMap[iconKey]) || UsersIcon;

    return (
        <Card className={cn('w-full', className)}>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
                <CardTitle className="text-sm font-medium text-muted-foreground">{title}</CardTitle>
                <div className="rounded-md bg-primary/10 p-2">
                    <Icon className="size-4 text-primary" />
                </div>
            </CardHeader>
            <CardContent>
                <div className="text-3xl font-bold">{value}</div>
                {subtitle ? <p className="mt-1 text-sm text-muted-foreground">{subtitle}</p> : null}
            </CardContent>
        </Card>
    );
};

interface HomeStatsSectionProps {
    stats: HomeStat[];
    className?: string;
}

const HomeStatsSection = ({ stats, className }: HomeStatsSectionProps) => {
    return (
        <section className={cn('border-b bg-background py-12', className)}>
            <div className="container mx-auto grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6">
                {stats.map((stat) => (
                    <StatsCard5
                        key={stat.key}
                        title={stat.label}
                        value={stat.value}
                        subtitle={stat.hint}
                        iconKey={stat.key}
                    />
                ))}
            </div>
        </section>
    );
};

export { HomeStatsSection, StatsCard5 };
