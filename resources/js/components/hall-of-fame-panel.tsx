import { TrophyIcon } from '@phosphor-icons/react';

import { PartyPanel, type PartyMember } from '@/components/party-panel';
import { Badge } from '@/components/ui/badge';
import { useTranslations } from '@/hooks/use-translations';

export type HallOfFameEntry = {
    id: number;
    name?: string | null;
    play_time?: string | null;
    points?: string | null;
    ot?: string | null;
    skin?: string | null;
    pokemon: PartyMember[];
};

type HallOfFamePanelProps = {
    entries: HallOfFameEntry[];
};

export function HallOfFamePanel({ entries }: HallOfFamePanelProps) {
    const { t } = useTranslations();

    if (entries.length === 0) {
        return <p className="text-sm text-muted-foreground">{t('No Hall of Fame entries yet')}</p>;
    }

    return (
        <div className="flex w-full min-w-0 flex-col gap-8">
            <div className="flex flex-col gap-1">
                <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                    <TrophyIcon className="size-4" weight="fill" />
                    {t('Hall of Fame')}
                </div>
                <div className="text-2xl font-bold">{t(':count entries', { count: entries.length })}</div>
            </div>

            {entries.map((entry) => (
                <div key={entry.id} className="flex flex-col gap-3 border border-border bg-muted/10 p-4">
                    <div className="flex flex-wrap items-center gap-2">
                        <Badge variant="default">{t('Entry No. :n', { n: entry.id + 1 })}</Badge>
                        {entry.name ? <span className="font-medium">{entry.name}</span> : null}
                    </div>
                    <dl className="grid gap-1 text-xs sm:grid-cols-3">
                        {entry.play_time ? (
                            <div>
                                <dt className="text-muted-foreground">{t('Play time')}</dt>
                                <dd className="font-medium">{entry.play_time}</dd>
                            </div>
                        ) : null}
                        {entry.ot ? (
                            <div>
                                <dt className="text-muted-foreground">{t('OT')}</dt>
                                <dd className="font-medium">{entry.ot}</dd>
                            </div>
                        ) : null}
                        {entry.points ? (
                            <div>
                                <dt className="text-muted-foreground">{t('Points')}</dt>
                                <dd className="font-medium">{entry.points}</dd>
                            </div>
                        ) : null}
                    </dl>
                    {entry.pokemon.length > 0 ? (
                        <PartyPanel party={entry.pokemon} showSummary={false} />
                    ) : (
                        <p className="text-sm text-muted-foreground">{t('No Pokémon recorded')}</p>
                    )}
                </div>
            ))}
        </div>
    );
}
