import {
    BookOpenIcon,
    CalendarBlankIcon,
    CheckCircleIcon,
    CoinsIcon,
    DeviceMobileIcon,
    GenderFemaleIcon,
    GenderIntersexIcon,
    GenderMaleIcon,
    IdentificationCardIcon,
    MapPinIcon,
    StarIcon,
    UserIcon,
    UsersIcon,
    XCircleIcon,
} from '@phosphor-icons/react';
import type { ComponentType, ReactNode, SVGProps } from 'react';

import { Badge } from '@/components/ui/badge';
import { cn } from '@/lib/utils';

export type PlayerDetails = Record<string, string | number | boolean | null | undefined>;

type IconComponent = ComponentType<SVGProps<SVGSVGElement> & { weight?: 'regular' | 'fill' }>;

type DetailField = {
    key: string;
    label: string;
    icon: IconComponent;
};

const fields: DetailField[] = [
    { key: 'Name', label: 'Name', icon: UserIcon },
    { key: 'RivalName', label: 'Rival', icon: UsersIcon },
    { key: 'Location', label: 'Location', icon: MapPinIcon },
    { key: 'Money', label: 'Money', icon: CoinsIcon },
    { key: 'Points', label: 'Points', icon: StarIcon },
    { key: 'GTSStars', label: 'GTS Stars', icon: StarIcon },
    { key: 'Gender', label: 'Gender', icon: GenderIntersexIcon },
    { key: 'OT', label: 'OT', icon: IdentificationCardIcon },
    { key: 'SaveCreated', label: 'Save created', icon: CalendarBlankIcon },
    { key: 'HasPokedex', label: 'Pokédex', icon: BookOpenIcon },
    { key: 'HasPokegear', label: 'Pokégear', icon: DeviceMobileIcon },
];

function genderIcon(value: string): IconComponent {
    const normalised = value.trim().toLowerCase();

    if (normalised === 'male' || normalised === '1') {
        return GenderMaleIcon;
    }

    if (normalised === 'female' || normalised === '2') {
        return GenderFemaleIcon;
    }

    return GenderIntersexIcon;
}

function formatValue(key: string, value: string): ReactNode {
    if (key === 'HasPokedex' || key === 'HasPokegear') {
        const isYes = value.trim().toLowerCase() === 'yes' || value === '1';

        return (
            <Badge variant={isYes ? 'default' : 'outline'}>
                {isYes ? (
                    <CheckCircleIcon data-icon="inline-start" weight="fill" />
                ) : (
                    <XCircleIcon data-icon="inline-start" weight="fill" />
                )}
                {isYes ? 'Yes' : 'No'}
            </Badge>
        );
    }

    if (key === 'Money') {
        const amount = Number(value.replace(/[^\d.-]/g, ''));

        if (!Number.isNaN(amount)) {
            return `₽${amount.toLocaleString()}`;
        }
    }

    if (key === 'GTSStars') {
        const stars = Number.parseInt(value, 10);

        if (!Number.isNaN(stars) && stars >= 0) {
            return (
                <div className="flex items-center gap-1" aria-label={`${stars} stars`}>
                    {Array.from({ length: Math.min(stars, 5) }, (_, index) => (
                        <StarIcon key={index} className="size-4 text-yellow-500" weight="fill" />
                    ))}
                    {stars === 0 ? <span className="text-muted-foreground">None</span> : null}
                </div>
            );
        }
    }

    return value;
}

type DetailsPanelProps = {
    details: PlayerDetails;
};

export function DetailsPanel({ details }: DetailsPanelProps) {
    const rows = fields
        .map((field) => {
            const raw = details[field.key];

            if (raw == null || raw === '') {
                return null;
            }

            return {
                ...field,
                value: String(raw),
            };
        })
        .filter((row): row is DetailField & { value: string } => row !== null);

    if (rows.length === 0) {
        return <p className="text-sm text-muted-foreground">No player details available</p>;
    }

    return (
        <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
            {rows.map((row) => {
                const Icon = row.key === 'Gender' ? genderIcon(row.value) : row.icon;

                return (
                    <div key={row.key} className="flex gap-3 border border-border bg-muted/20 p-3">
                        <div className="flex size-10 shrink-0 items-center justify-center border border-border bg-background">
                            <Icon className="size-5 text-muted-foreground" weight="fill" />
                        </div>
                        <div className="flex min-w-0 flex-1 flex-col gap-1">
                            <div className="text-xs text-muted-foreground">{row.label}</div>
                            <div className={cn('text-sm font-medium', row.key !== 'GTSStars' && 'truncate')}>
                                {formatValue(row.key, row.value)}
                            </div>
                        </div>
                    </div>
                );
            })}
        </div>
    );
}
