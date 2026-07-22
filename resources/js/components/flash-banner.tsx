import { usePage } from '@inertiajs/react';

import { cn } from '@/lib/utils';
import type { SharedPageProps } from '@/types';

const styleClasses: Record<string, string> = {
    success: 'border-green-600/30 bg-green-500/10 text-green-800 dark:text-green-300',
    warning: 'border-amber-600/30 bg-amber-500/10 text-amber-900 dark:text-amber-200',
    danger: 'border-destructive/40 bg-destructive/10 text-destructive',
    error: 'border-destructive/40 bg-destructive/10 text-destructive',
    info: 'border-border bg-muted/40 text-foreground',
};

export default function FlashBanner() {
    const { flash } = usePage<SharedPageProps>().props;

    const message =
        flash.banner ||
        flash.error ||
        flash.success ||
        flash.warning ||
        flash.status ||
        null;

    if (! message) {
        return null;
    }

    const style =
        flash.bannerStyle ||
        (flash.error ? 'danger' : null) ||
        (flash.success ? 'success' : null) ||
        (flash.warning ? 'warning' : null) ||
        'info';

    return (
        <div className="border-b border-border bg-background">
            <div className="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
                <div
                    role="status"
                    className={cn('border px-4 py-3 text-sm', styleClasses[style] ?? styleClasses.info)}
                >
                    {message}
                </div>
            </div>
        </div>
    );
}
