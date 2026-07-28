import { Head } from '@inertiajs/react';
import { FloppyDiskIcon } from '@phosphor-icons/react';
import { useState } from 'react';

import { BagPanel, type BagItem } from '@/components/bag-panel';
import { BoxPanel, type BoxEntry } from '@/components/box-panel';
import { DaycarePanel, type DaycareEntry } from '@/components/daycare-panel';
import { DetailsPanel, type PlayerDetails } from '@/components/details-panel';
import { HallOfFamePanel, type HallOfFameEntry } from '@/components/hall-of-fame-panel';
import { PartyPanel, type PartyMember } from '@/components/party-panel';
import { PokedexPanel, type PokedexDefinition } from '@/components/pokedex-panel';
import { RoamingPanel, type RoamingEntry } from '@/components/roaming-panel';
import { StatisticsPanel, type StatisticItem } from '@/components/statistics-panel';
import { TrophiesPanel, type TrophyItem } from '@/components/trophies-panel';
import { Card, CardContent } from '@/components/ui/card';
import {
    WorldPanel,
    type ApricornEntry,
    type BerryEntry,
    type ItemDataPayload,
} from '@/components/world-panel';
import { useTranslations } from '@/hooks/use-translations';

type GameSavePayload = {
    available: boolean;
    message?: string;
    last_synced?: string;
    caught_count?: number;
    seen_count?: number;
    party?: PartyMember[];
    details?: PlayerDetails;
    pokedexes?: PokedexDefinition[];
    statistics?: StatisticItem[];
    trophies?: {
        achieved: number;
        total: number;
        items: TrophyItem[];
    };
    box?: BoxEntry[];
    items?: BagItem[];
    daycare?: DaycareEntry[];
    hall_of_fame?: HallOfFameEntry[];
    roaming?: RoamingEntry[];
    apricorns?: ApricornEntry[];
    berries?: BerryEntry[];
    itemdata?: ItemDataPayload;
};

type Props = {
    gameSave: GameSavePayload;
};

const tabKeys = [
    ['party', 'Party'],
    ['details', 'Details'],
    ['box', 'Box'],
    ['bag', 'Bag'],
    ['daycare', 'Daycare'],
    ['pokedex', 'Pokédex'],
    ['hall_of_fame', 'Hall of Fame'],
    ['roaming', 'Roaming'],
    ['world', 'World'],
    ['trophies', 'Trophies'],
    ['statistics', 'Statistics'],
] as const;

export default function SaveIndex({ gameSave }: Props) {
    const { t } = useTranslations();
    const [saveTab, setSaveTab] = useState('party');

    return (
        <>
            <Head title={t('My Save')} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-2">
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <FloppyDiskIcon className="size-5" weight="fill" />
                        <span className="text-sm">{t('Game')}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{t('My Save')}</h1>
                    <p className="text-sm text-muted-foreground">
                        {t('Full details of your synced Pokémon 3D game save.')}
                    </p>
                </div>

                <Card className="w-full py-0">
                    <CardContent className="p-5">
                        {!gameSave.available ? (
                            <p className="text-sm text-muted-foreground">
                                {gameSave.message ?? t('No game save is available yet.')}
                            </p>
                        ) : (
                            <>
                                <div className="flex flex-wrap gap-4 text-sm text-muted-foreground">
                                    <span>
                                        {t('Last synced:')} {gameSave.last_synced}
                                    </span>
                                    <span>
                                        {t('Caught:')} {gameSave.caught_count ?? 0}
                                    </span>
                                    <span>
                                        {t('Seen:')} {gameSave.seen_count ?? 0}
                                    </span>
                                </div>

                                <div className="mt-4 flex flex-wrap gap-3 border-b text-sm">
                                    {tabKeys.map(([key, label]) => (
                                        <button
                                            key={key}
                                            type="button"
                                            className={`pb-2 ${saveTab === key ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground'}`}
                                            onClick={() => setSaveTab(key)}
                                        >
                                            {t(label)}
                                        </button>
                                    ))}
                                </div>

                                <div className="mt-4 min-w-0 w-full overflow-x-auto text-sm text-foreground">
                                    {saveTab === 'party' && <PartyPanel party={gameSave.party ?? []} />}
                                    {saveTab === 'details' && (
                                        <DetailsPanel details={gameSave.details ?? {}} />
                                    )}
                                    {saveTab === 'box' && <BoxPanel box={gameSave.box ?? []} />}
                                    {saveTab === 'bag' && <BagPanel items={gameSave.items ?? []} />}
                                    {saveTab === 'daycare' && (
                                        <DaycarePanel daycare={gameSave.daycare ?? []} />
                                    )}
                                    {saveTab === 'pokedex' && (
                                        <PokedexPanel pokedexes={gameSave.pokedexes ?? []} />
                                    )}
                                    {saveTab === 'hall_of_fame' && (
                                        <HallOfFamePanel entries={gameSave.hall_of_fame ?? []} />
                                    )}
                                    {saveTab === 'roaming' && (
                                        <RoamingPanel roaming={gameSave.roaming ?? []} />
                                    )}
                                    {saveTab === 'world' && (
                                        <WorldPanel
                                            apricorns={gameSave.apricorns ?? []}
                                            berries={gameSave.berries ?? []}
                                            itemdata={gameSave.itemdata ?? { count: 0, items: [] }}
                                        />
                                    )}
                                    {saveTab === 'trophies' && (
                                        <TrophiesPanel
                                            trophies={gameSave.trophies?.items ?? []}
                                            achievedCount={gameSave.trophies?.achieved ?? 0}
                                            totalCount={gameSave.trophies?.total ?? 0}
                                        />
                                    )}
                                    {saveTab === 'statistics' && (
                                        <StatisticsPanel statistics={gameSave.statistics ?? []} />
                                    )}
                                </div>
                            </>
                        )}
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
