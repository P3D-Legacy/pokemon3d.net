import { cn } from '@/lib/utils';

export default function InputError({
    message,
    className,
}: {
    message?: string;
    className?: string;
}) {
    if (! message) {
        return null;
    }

    return <p className={cn('text-sm text-red-600 dark:text-red-400', className)}>{message}</p>;
}
