import { HandbagIcon } from '@phosphor-icons/react';

import { Badge } from '@/components/ui/badge';

export type BagItem = {
    id: string;
    name: string;
    amount: number;
};

type BagPanelProps = {
    items: BagItem[];
};

export function BagPanel({ items }: BagPanelProps) {
    if (items.length === 0) {
        return <p className="text-sm text-muted-foreground">Bag is empty</p>;
    }

    return (
        <div className="flex w-full min-w-0 flex-col gap-4">
            <div className="flex flex-col gap-1">
                <div className="flex items-center gap-1.5 text-sm text-muted-foreground">
                    <HandbagIcon className="size-4" weight="fill" />
                    Bag
                </div>
                <div className="text-2xl font-bold">{items.length} stacks</div>
            </div>

            <div className="grid w-full min-w-0 grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                {items.map((item) => (
                    <div
                        key={`${item.id}-${item.name}`}
                        className="flex min-w-0 items-center justify-between gap-3 border border-border bg-muted/20 p-3"
                    >
                        <div className="min-w-0">
                            <div className="truncate font-medium">{item.name}</div>
                            <div className="truncate text-xs text-muted-foreground">ID {item.id}</div>
                        </div>
                        <Badge variant="secondary" className="shrink-0">
                            ×{item.amount}
                        </Badge>
                    </div>
                ))}
            </div>
        </div>
    );
}
