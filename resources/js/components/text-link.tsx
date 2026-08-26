import { Link } from '@inertiajs/react';
import type { ComponentProps } from 'react';

import { cn } from '@/lib/utils';

export default function TextLink({ className, children, ...props }: ComponentProps<typeof Link>) {
    return (
        <Link className={cn('text-sm text-slate-600 underline hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-100', className)} {...props}>
            {children}
        </Link>
    );
}
