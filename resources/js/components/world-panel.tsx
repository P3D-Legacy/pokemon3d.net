import { PlantIcon, MapPinIcon, TreeEvergreenIcon } from '@phosphor-icons/react';

import { Badge } from '@/components/ui/badge';

export type ApricornEntry = {
    type: 'tree' | 'kurt';
    map_path?: string | null;
    position?: string | null;
    amounts?: Record<string, number> | null;
    timestamp?: string | null;
};

export type BerryEntry = {
    map_path: string;
    position?: string | null;
    berry_id: string;
    berry_name: string;
    berry_count: number;
    watered_stages: number;
    timestamp?: string | null;
};

export type ItemDataPayload = {
    count: number;
    items: Array<{
        map_path: string;
        item_id: string;
        item_name: string;
    }>;
};

type WorldPanelProps = {
    apricorns: ApricornEntry[];
    berries: BerryEntry[];
    itemdata: ItemDataPayload;
};

export function WorldPanel({ apricorns, berries, itemdata }: WorldPanelProps) {
    const hasContent = apricorns.length > 0 || berries.length > 0 || itemdata.count > 0;

    if (!hasContent) {
        return <p className="text-sm text-muted-foreground">No world activity recorded</p>;
    }

    return (
        <div className="flex w-full min-w-0 flex-col gap-8">
            <section className="flex flex-col gap-3">
                <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                    <TreeEvergreenIcon className="size-4" weight="fill" />
                    Apricorns
                </div>
                {apricorns.length === 0 ? (
                    <p className="text-sm text-muted-foreground">No apricorn data</p>
                ) : (
                    <div className="grid gap-2 sm:grid-cols-2">
                        {apricorns.map((entry, index) => (
                            <div
                                key={`${entry.type}-${entry.map_path ?? 'kurt'}-${index}`}
                                className="border border-border bg-muted/20 p-3 text-sm"
                            >
                                <div className="mb-2 flex items-center gap-2">
                                    <Badge variant={entry.type === 'kurt' ? 'default' : 'secondary'}>
                                        {entry.type === 'kurt' ? 'Kurt' : 'Tree'}
                                    </Badge>
                                </div>
                                {entry.map_path ? (
                                    <p className="break-all text-xs text-muted-foreground">{entry.map_path}</p>
                                ) : null}
                                {entry.position ? (
                                    <p className="text-xs text-muted-foreground">Pos {entry.position}</p>
                                ) : null}
                                {entry.amounts ? (
                                    <div className="mt-2 flex flex-wrap gap-1">
                                        {Object.entries(entry.amounts)
                                            .filter(([, amount]) => amount > 0)
                                            .map(([colour, amount]) => (
                                                <Badge key={colour} variant="outline">
                                                    {colour} ×{amount}
                                                </Badge>
                                            ))}
                                    </div>
                                ) : null}
                            </div>
                        ))}
                    </div>
                )}
            </section>

            <section className="flex flex-col gap-3">
                <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                    <PlantIcon className="size-4" weight="fill" />
                    Planted berries
                </div>
                {berries.length === 0 ? (
                    <p className="text-sm text-muted-foreground">No planted berries</p>
                ) : (
                    <div className="grid gap-2 sm:grid-cols-2">
                        {berries.map((berry, index) => (
                            <div
                                key={`${berry.map_path}-${berry.berry_id}-${index}`}
                                className="flex items-start justify-between gap-3 border border-border bg-muted/20 p-3"
                            >
                                <div className="min-w-0">
                                    <div className="font-medium">{berry.berry_name}</div>
                                    <p className="break-all text-xs text-muted-foreground">{berry.map_path}</p>
                                </div>
                                <Badge variant="secondary">×{berry.berry_count}</Badge>
                            </div>
                        ))}
                    </div>
                )}
            </section>

            <section className="flex flex-col gap-3">
                <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                    <MapPinIcon className="size-4" weight="fill" />
                    Collected map items
                </div>
                <div className="text-sm text-muted-foreground">{itemdata.count} pickups recorded</div>
                {itemdata.items.length > 0 ? (
                    <div className="grid max-h-80 gap-2 overflow-y-auto sm:grid-cols-2">
                        {itemdata.items.map((item, index) => (
                            <div
                                key={`${item.map_path}-${item.item_id}-${index}`}
                                className="border border-border bg-muted/20 p-3 text-sm"
                            >
                                <div className="font-medium">{item.item_name}</div>
                                <p className="break-all text-xs text-muted-foreground">{item.map_path}</p>
                            </div>
                        ))}
                    </div>
                ) : null}
            </section>
        </div>
    );
}
