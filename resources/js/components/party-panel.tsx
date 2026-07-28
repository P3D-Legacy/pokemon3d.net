import {
    CirclesFourIcon,
    EggIcon,
    GenderFemaleIcon,
    GenderIntersexIcon,
    GenderMaleIcon,
    HeartIcon,
    ImageBrokenIcon,
    LightningIcon,
    SparkleIcon,
} from '@phosphor-icons/react';
import type { ComponentType, SVGProps } from 'react';
import { useState } from 'react';

import { Badge } from '@/components/ui/badge';
import { useTranslations } from '@/hooks/use-translations';

export type PartyMember = {
    id: number;
    name: string;
    nickname?: string | null;
    level: number;
    gender?: string | null;
    nature?: string | null;
    ability?: string | null;
    friendship?: string | null;
    experience?: string | null;
    status?: string | null;
    shiny: boolean;
    is_egg: boolean;
    sprite_url: string;
};

type IconComponent = ComponentType<SVGProps<SVGSVGElement> & { weight?: 'regular' | 'fill' }>;

type PartyPanelProps = {
    party: PartyMember[];
    title?: string;
    showSummary?: boolean;
};

const FRAME_SIZE = 96;
const SHEET_SIZE = 192;

function genderIcon(gender?: string | null): IconComponent | null {
    const value = gender?.toLowerCase();

    if (value === 'male') {
        return GenderMaleIcon;
    }

    if (value === 'female') {
        return GenderFemaleIcon;
    }

    if (value === 'genderless') {
        return GenderIntersexIcon;
    }

    return null;
}

function PartySprite({ member }: { member: PartyMember }) {
    const { t } = useTranslations();
    const [failed, setFailed] = useState(false);
    const label = `${member.is_egg ? t('Egg') : member.nickname || member.name}${member.shiny ? t(', shiny') : ''}`;

    if (failed || !member.sprite_url) {
        return (
            <div className="flex size-24 items-center justify-center border border-border bg-muted">
                {member.is_egg ? (
                    <EggIcon className="size-8 text-muted-foreground" weight="fill" />
                ) : (
                    <ImageBrokenIcon className="size-8 text-muted-foreground" weight="fill" />
                )}
            </div>
        );
    }

    if (member.is_egg) {
        return (
            <div className="flex size-24 shrink-0 items-center justify-center overflow-hidden border border-border bg-muted">
                <img
                    src={member.sprite_url}
                    alt={label}
                    className="size-full object-contain"
                    style={{ imageRendering: 'pixelated' }}
                    onError={() => setFailed(true)}
                />
            </div>
        );
    }

    return (
        <div className="relative size-24 shrink-0 overflow-hidden border border-border bg-muted">
            <img
                src={member.sprite_url}
                alt={label}
                className="absolute top-0 left-0 max-w-none"
                style={{
                    width: SHEET_SIZE,
                    height: SHEET_SIZE,
                    transform: member.shiny ? `translateY(-${FRAME_SIZE}px)` : 'translateY(0)',
                    imageRendering: 'pixelated',
                }}
                onError={() => setFailed(true)}
            />
        </div>
    );
}

export function PartyPanel({ party, title, showSummary = true }: PartyPanelProps) {
    const { t } = useTranslations();
    const resolvedTitle = title ?? t('Party');

    if (party.length === 0) {
        return <p className="text-sm text-muted-foreground">{t('No Pokémon in party')}</p>;
    }

    return (
        <div className="flex w-full min-w-0 flex-col gap-4">
            {showSummary ? (
                <div className="flex flex-col gap-1">
                    <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                        <CirclesFourIcon className="size-4" weight="fill" />
                        {resolvedTitle}
                    </div>
                    <div className="text-2xl font-bold">
                        {resolvedTitle === t('Party') ? `${party.length} / 6` : party.length}
                    </div>
                </div>
            ) : null}

            <div className="grid w-full min-w-0 grid-cols-1 gap-3 sm:grid-cols-2">
                {party.map((member, index) => {
                    const GenderIcon = genderIcon(member.gender);
                    const displayName = member.is_egg ? t('Egg') : member.nickname || member.name;
                    const showSpecies = !member.is_egg && Boolean(member.nickname);

                    return (
                        <div
                            key={`${member.id}-${member.nickname ?? 'mon'}-${index}`}
                            className="flex min-w-0 gap-3 border border-border bg-muted/20 p-3"
                        >
                            <PartySprite member={member} />

                            <div className="flex min-w-0 flex-1 flex-col gap-2">
                                <div className="flex min-w-0 items-start justify-between gap-2">
                                    <div className="min-w-0">
                                        <div className="flex min-w-0 items-center gap-1.5">
                                            <div className="truncate font-medium">{displayName}</div>
                                            {GenderIcon ? <GenderIcon className="size-4 shrink-0 text-muted-foreground" weight="fill" /> : null}
                                        </div>
                                        {showSpecies ? (
                                            <div className="truncate text-xs text-muted-foreground">{member.name}</div>
                                        ) : null}
                                    </div>
                                    {!member.is_egg ? (
                                        <Badge variant="secondary" className="shrink-0">
                                            {t('Lv :level', { level: member.level })}
                                        </Badge>
                                    ) : null}
                                </div>

                                <div className="flex flex-wrap gap-1.5">
                                    {member.shiny ? (
                                        <Badge variant="default">
                                            <SparkleIcon data-icon="inline-start" weight="fill" />
                                            {t('Shiny')}
                                        </Badge>
                                    ) : null}
                                    {member.is_egg ? (
                                        <Badge variant="outline">
                                            <EggIcon data-icon="inline-start" weight="fill" />
                                            {t('Egg')}
                                        </Badge>
                                    ) : null}
                                    {member.status ? <Badge variant="outline">{member.status}</Badge> : null}
                                </div>

                                <dl className="grid min-w-0 gap-1 text-xs">
                                    {member.nature ? (
                                        <div className="flex min-w-0 justify-between gap-2">
                                            <dt className="text-muted-foreground">{t('Nature')}</dt>
                                            <dd className="truncate font-medium">{member.nature}</dd>
                                        </div>
                                    ) : null}
                                    {member.ability ? (
                                        <div className="flex min-w-0 items-center justify-between gap-2">
                                            <dt className="flex items-center gap-1 text-muted-foreground">
                                                <LightningIcon className="size-3" weight="fill" />
                                                {t('Ability')}
                                            </dt>
                                            <dd className="truncate font-medium">{member.ability}</dd>
                                        </div>
                                    ) : null}
                                    {member.friendship ? (
                                        <div className="flex min-w-0 items-center justify-between gap-2">
                                            <dt className="flex items-center gap-1 text-muted-foreground">
                                                <HeartIcon className="size-3" weight="fill" />
                                                {t('Friendship')}
                                            </dt>
                                            <dd className="truncate font-medium">{member.friendship}</dd>
                                        </div>
                                    ) : null}
                                </dl>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
