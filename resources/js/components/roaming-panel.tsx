import { CompassIcon, SparkleIcon } from '@phosphor-icons/react';

import { PartyPanel, type PartyMember } from '@/components/party-panel';
import { Badge } from '@/components/ui/badge';
import { useTranslations } from '@/hooks/use-translations';

export type RoamingEntry = {
    roamer_id: string;
    pokemon_id: string;
    level: number;
    world_id: number;
    level_file: string;
    music_loop: string;
    shiny: boolean;
    pokemon?: PartyMember | null;
};

type RoamingPanelProps = {
    roaming: RoamingEntry[];
};

export function RoamingPanel({ roaming }: RoamingPanelProps) {
    const { t } = useTranslations();

    if (roaming.length === 0) {
        return <p className="text-sm text-muted-foreground">{t('No roaming Pokémon')}</p>;
    }

    return (
        <div className="flex w-full min-w-0 flex-col gap-6">
            <div className="flex flex-col gap-1">
                <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                    <CompassIcon className="size-4" weight="fill" />
                    {t('Roaming')}
                </div>
                <div className="text-2xl font-bold">{t(':count active', { count: roaming.length })}</div>
            </div>

            {roaming.map((entry) => (
                <div
                    key={`${entry.roamer_id}-${entry.pokemon_id}-${entry.level_file}`}
                    className="flex flex-col gap-3 border border-border bg-muted/10 p-4"
                >
                    <div className="flex flex-wrap items-center gap-2">
                        <Badge variant="secondary">{t('World :id', { id: entry.world_id })}</Badge>
                        <Badge variant="outline">{t('Lv :level', { level: entry.level })}</Badge>
                        {entry.shiny ? (
                            <Badge variant="default">
                                <SparkleIcon data-icon="inline-start" weight="fill" />
                                {t('Shiny')}
                            </Badge>
                        ) : null}
                    </div>
                    <dl className="grid gap-1 text-xs sm:grid-cols-2">
                        <div>
                            <dt className="text-muted-foreground">{t('Location')}</dt>
                            <dd className="break-all font-medium">{entry.level_file}</dd>
                        </div>
                        <div>
                            <dt className="text-muted-foreground">{t('Roamer ID')}</dt>
                            <dd className="font-medium">{entry.roamer_id}</dd>
                        </div>
                    </dl>
                    {entry.pokemon ? (
                        <PartyPanel party={[entry.pokemon]} showSummary={false} />
                    ) : (
                        <p className="text-sm text-muted-foreground">{t('Pokémon data unavailable')}</p>
                    )}
                </div>
            ))}
        </div>
    );
}
