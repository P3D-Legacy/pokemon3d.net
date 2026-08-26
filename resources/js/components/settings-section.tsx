import type { PropsWithChildren, ReactNode } from 'react';

export default function SettingsSection({
    title,
    description,
    children,
}: PropsWithChildren<{ title: string; description: ReactNode }>) {
    return (
        <div className="md:grid md:grid-cols-3 md:gap-6">
            <div className="md:col-span-1">
                <h3 className="text-lg font-medium text-foreground">{title}</h3>
                <div className="mt-1 text-sm text-muted-foreground">{description}</div>
            </div>
            <div className="mt-5 md:col-span-2 md:mt-0">
                <div className="bg-muted px-4 py-5 border border-gray-200 dark:border-gray-800 sm:p-6">{children}</div>
            </div>
        </div>
    );
}
