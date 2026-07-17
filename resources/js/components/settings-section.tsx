import type { PropsWithChildren, ReactNode } from 'react';

export default function SettingsSection({
    title,
    description,
    children,
}: PropsWithChildren<{ title: string; description: ReactNode }>) {
    return (
        <div className="md:grid md:grid-cols-3 md:gap-6">
            <div className="md:col-span-1">
                <h3 className="text-lg font-medium text-slate-900 dark:text-slate-100">{title}</h3>
                <div className="mt-1 text-sm text-slate-600 dark:text-slate-400">{description}</div>
            </div>
            <div className="mt-5 md:col-span-2 md:mt-0">
                <div className="bg-white px-4 py-5 shadow sm:rounded-md sm:p-6 dark:bg-slate-900">{children}</div>
            </div>
        </div>
    );
}
