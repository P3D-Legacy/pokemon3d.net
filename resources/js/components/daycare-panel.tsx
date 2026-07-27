import { HouseLineIcon } from '@phosphor-icons/react';

import { PartyPanel, type PartyMember } from '@/components/party-panel';
import { Badge } from '@/components/ui/badge';

export type DaycareEntry = {
    daycare_id: number;
    slot: number | string;
    is_egg: boolean;
    pokemon: PartyMember;
};

type DaycarePanelProps = {
    daycare: DaycareEntry[];
};

export function DaycarePanel({ daycare }: DaycarePanelProps) {
    if (daycare.length === 0) {
        return <p className="text-sm text-muted-foreground">No Pokémon at the daycare</p>;
    }

    const byDaycare = daycare.reduce<Record<number, DaycareEntry[]>>((groups, entry) => {
        groups[entry.daycare_id] ??= [];
        groups[entry.daycare_id].push(entry);

        return groups;
    }, {});

    return (
        <div className="flex w-full min-w-0 flex-col gap-8">
            <div className="flex flex-col gap-1">
                <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                    <HouseLineIcon className="size-4" weight="fill" />
                    Daycare
                </div>
                <div className="text-2xl font-bold">{daycare.length} entries</div>
            </div>

            {Object.keys(byDaycare)
                .map(Number)
                .sort((a, b) => a - b)
                .map((daycareId) => (
                    <div key={daycareId} className="flex flex-col gap-3">
                        <div className="flex flex-wrap items-center gap-2">
                            <Badge variant="secondary">Daycare {daycareId}</Badge>
                            {byDaycare[daycareId].map((entry, index) => (
                                <Badge key={`${entry.slot}-${index}`} variant="outline">
                                    Slot {String(entry.slot)}
                                </Badge>
                            ))}
                        </div>
                        <PartyPanel
                            party={byDaycare[daycareId].map((entry) => entry.pokemon)}
                            showSummary={false}
                        />
                    </div>
                ))}
        </div>
    );
}
