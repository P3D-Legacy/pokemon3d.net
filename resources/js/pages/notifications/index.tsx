import { Head, Link, router } from '@inertiajs/react';
import { ArrowSquareOutIcon, EyeSlashIcon } from '@phosphor-icons/react';

import { Button } from '@/components/ui/button';
import { dismiss, dismissAll, open } from '@/routes/notifications';
import type { Paginated } from '@/types';

type NotificationItem = {
    id: string;
    message: string;
    icon: string | null;
    url: string | null;
    read_at: string | null;
    created_for_humans: string;
};

type Props = {
    notifications: Paginated<NotificationItem>;
};

export default function NotificationsIndex({ notifications }: Props) {
    return (
        <>
            <Head title="Notifications" />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-4 flex justify-end">
                    <Button type="button" variant="outline" onClick={() => router.post(dismissAll.url())}>
                        Dismiss all
                    </Button>
                </div>

                <div className="overflow-hidden rounded-lg border border-slate-200 bg-white shadow dark:border-slate-700 dark:bg-slate-900">
                    {notifications.data.length === 0 && (
                        <div className="p-4 text-center text-slate-500">No notifications</div>
                    )}

                    {notifications.data.map((notification) => (
                        <div
                            key={notification.id}
                            className={`flex items-center border-b px-4 py-2 dark:border-slate-700 ${notification.read_at ? 'bg-slate-100 dark:bg-slate-800/60' : ''}`}
                        >
                            <div className="flex-grow">
                                <p
                                    className="font-medium text-slate-900 dark:text-slate-200"
                                    dangerouslySetInnerHTML={{ __html: notification.message }}
                                />
                                <div className="text-xs text-slate-500">{notification.created_for_humans}</div>
                            </div>
                            <div className="ml-5 flex items-center gap-2">
                                {notification.url && (
                                    <Button
                                        type="button"
                                        variant="brand"
                                        size="sm"
                                        onClick={() => router.post(open.url(notification.id))}
                                    >
                                        <ArrowSquareOutIcon className="size-4" />
                                        Open
                                    </Button>
                                )}
                                {! notification.read_at && (
                                    <Button
                                        type="button"
                                        variant="secondary"
                                        size="sm"
                                        onClick={() => router.post(dismiss.url(notification.id))}
                                    >
                                        <EyeSlashIcon className="size-4" />
                                        Dismiss
                                    </Button>
                                )}
                            </div>
                        </div>
                    ))}
                </div>

                {notifications.links.length > 3 && (
                    <div className="mt-6 flex flex-wrap justify-center gap-2">
                        {notifications.links.map((link, index) =>
                            link.url ? (
                                <Link
                                    key={`${link.label}-${index}`}
                                    href={link.url}
                                    className={`rounded px-3 py-1 text-sm ${link.active ? 'bg-green-600 text-white' : 'bg-white text-slate-700 dark:bg-slate-900 dark:text-slate-200'}`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ) : (
                                <span
                                    key={`${link.label}-${index}`}
                                    className="rounded px-3 py-1 text-sm text-slate-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ),
                        )}
                    </div>
                )}
            </div>
        </>
    );
}
