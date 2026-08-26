import { ArchiveIcon } from '@phosphor-icons/react';

import { PartyPanel, type PartyMember } from '@/components/party-panel';
import { Badge } from '@/components/ui/badge';
import { useTranslations } from '@/hooks/use-translations';

export type BoxEntry = {
    box_index: number;
    position: number;
    pokemon: PartyMember;
};

type BoxPanelProps = {
    box: BoxEntry[];
};

export function BoxPanel({ box }: BoxPanelProps) {
    const { t } = useTranslations();

    if (box.length === 0) {
        return <p className="text-sm text-muted-foreground">{t('No Pokémon in the PC')}</p>;
    }

    const boxes = box.reduce<Record<number, PartyMember[]>>((groups, entry) => {
        const key = entry.box_index;
        groups[key] ??= [];
        groups[key].push(entry.pokemon);

        return groups;
    }, {});

    const boxIndexes = Object.keys(boxes)
        .map(Number)
        .sort((a, b) => a - b);

    return (
        <div className="flex w-full min-w-0 flex-col gap-8">
            <div className="flex flex-col gap-1">
                <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                    <ArchiveIcon className="size-4" weight="fill" />
                    {t('PC Storage')}
                </div>
                <div className="text-2xl font-bold">{t(':count Pokémon', { count: box.length })}</div>
            </div>

            {boxIndexes.map((boxIndex) => (
                <div key={boxIndex} className="flex flex-col gap-3">
                    <div className="flex items-center gap-2">
                        <Badge variant="secondary">{t('Box :n', { n: boxIndex + 1 })}</Badge>
                        <span className="text-xs text-muted-foreground">
                            {t(':count stored', { count: boxes[boxIndex].length })}
                        </span>
                    </div>
                    <PartyPanel party={boxes[boxIndex]} showSummary={false} />
                </div>
            ))}
        </div>
    );
}
