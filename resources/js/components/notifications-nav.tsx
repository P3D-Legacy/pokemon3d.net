import { router, usePage } from '@inertiajs/react';
import { ArrowSquareOutIcon, BellIcon, EyeSlashIcon } from '@phosphor-icons/react';
import { useCallback, useState } from 'react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import { dismiss, dismissAll, index, open } from '@/routes/notifications';
import type { SharedPageProps } from '@/types';

type NotificationItem = {
    id: string;
    message: string;
    icon: string | null;
    url: string | null;
    read_at: string | null;
    created_for_humans: string;
};

type Props = {
    variant?: 'dark' | 'light';
    className?: string;
};

export function NotificationsNav({ variant = 'light', className }: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const { t } = useTranslations();
    const unreadCount = auth.user?.unread_notifications_count ?? 0;
    const isLight = variant === 'light';

    const [isOpen, setIsOpen] = useState(false);
    const [items, setItems] = useState<NotificationItem[]>([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const loadNotifications = useCallback(async () => {
        setLoading(true);
        setError(null);

        try {
            const response = await fetch(index.url(), {
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (! response.ok) {
                throw new Error(t('Unable to load notifications.'));
            }

            const payload = (await response.json()) as { notifications: NotificationItem[] };
            setItems(payload.notifications ?? []);
        } catch {
            setError(t('Unable to load notifications.'));
            setItems([]);
        } finally {
            setLoading(false);
        }
    }, [t]);

    const handleOpenChange = (nextOpen: boolean) => {
        setIsOpen(nextOpen);

        if (nextOpen) {
            void loadNotifications();
        }
    };

    const refreshAfterAction = () => {
        void loadNotifications();
    };

    return (
        <Popover open={isOpen} onOpenChange={handleOpenChange}>
            <PopoverTrigger asChild>
                <Button
                    type="button"
                    variant="ghost"
                    size="icon"
                    className={cn(
                        'relative',
                        ! isLight && 'text-white hover:bg-white/10 hover:text-white',
                        className,
                    )}
                    aria-label={t('Notifications')}
                >
                    <BellIcon className="size-5" />
                    {unreadCount > 0 ? (
                        <Badge className="absolute -top-1 -right-1 h-5 min-w-5 px-1">
                            {unreadCount > 99 ? '99+' : unreadCount}
                        </Badge>
                    ) : null}
                </Button>
            </PopoverTrigger>

            <PopoverContent align="end" className="w-[min(100vw-2rem,22rem)] gap-0 p-0">
                <div className="flex items-center justify-between gap-2 border-b border-border px-3 py-2.5">
                    <div className="text-sm font-medium">{t('Notifications')}</div>
                    {unreadCount > 0 ? (
                        <Button
                            type="button"
                            variant="ghost"
                            size="xs"
                            onClick={() =>
                                router.post(dismissAll.url(), {}, {
                                    preserveScroll: true,
                                    onSuccess: refreshAfterAction,
                                })
                            }
                        >
                            {t('Dismiss all')}
                        </Button>
                    ) : null}
                </div>

                <div className="max-h-80 overflow-y-auto">
                    {loading ? (
                        <div className="px-3 py-8 text-center text-sm text-muted-foreground">{t('Loading…')}</div>
                    ) : null}

                    {! loading && error ? (
                        <div className="px-3 py-8 text-center text-sm text-muted-foreground">{error}</div>
                    ) : null}

                    {! loading && ! error && items.length === 0 ? (
                        <div className="px-3 py-8 text-center text-sm text-muted-foreground">{t('No notifications')}</div>
                    ) : null}

                    {! loading && ! error
                        ? items.map((notification) => (
                              <div
                                  key={notification.id}
                                  className={cn(
                                      'flex flex-col gap-2 border-b border-border px-3 py-3 last:border-b-0',
                                      notification.read_at ? 'bg-muted/30' : 'bg-background',
                                  )}
                              >
                                  <div className="min-w-0">
                                      <p className="text-sm leading-snug text-foreground">{notification.message}</p>
                                      <p className="mt-1 text-xs text-muted-foreground">
                                          {notification.created_for_humans}
                                      </p>
                                  </div>
                                  <div className="flex flex-wrap gap-1.5">
                                      {notification.url ? (
                                          <Button
                                              type="button"
                                              size="xs"
                                              onClick={() =>
                                                  router.post(open.url(notification.id), {}, {
                                                      preserveScroll: true,
                                                  })
                                              }
                                          >
                                              <ArrowSquareOutIcon data-icon="inline-start" />
                                              {t('Open')}
                                          </Button>
                                      ) : null}
                                      {! notification.read_at ? (
                                          <Button
                                              type="button"
                                              variant="secondary"
                                              size="xs"
                                              onClick={() =>
                                                  router.post(dismiss.url(notification.id), {}, {
                                                      preserveScroll: true,
                                                      onSuccess: refreshAfterAction,
                                                  })
                                              }
                                          >
                                              <EyeSlashIcon data-icon="inline-start" />
                                              {t('Dismiss')}
                                          </Button>
                                      ) : null}
                                  </div>
                              </div>
                          ))
                        : null}
                </div>
            </PopoverContent>
        </Popover>
    );
}
