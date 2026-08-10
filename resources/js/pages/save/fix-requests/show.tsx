import { Form, Head, Link } from '@inertiajs/react';
import { FloppyDiskIcon } from '@phosphor-icons/react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { useTranslations } from '@/hooks/use-translations';
import {
    cancel as cancelFixRequest,
    index as fixRequestsIndex,
    notifications as updateNotifications,
} from '@/routes/save/fix-requests';

type FixRequest = {
    uuid: string;
    description: string;
    status: string;
    status_label: string;
    assignee: { username: string } | null;
    notify_database: boolean;
    notify_mail: boolean;
    consent_accepted_at: string | null;
    resolved_at: string | null;
    created_at: string | null;
    updated_at: string | null;
    can_cancel: boolean;
};

type Props = {
    fixRequest: FixRequest;
    consentText: string;
};

export default function SaveFixRequestShow({ fixRequest, consentText }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Save fix request')} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={fixRequestsIndex.url()}>{t('Back to requests')}</Link>
                    </Button>
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <FloppyDiskIcon className="size-5" weight="fill" />
                            <span className="text-sm">{t('Game')}</span>
                        </div>
                        <div className="flex flex-wrap items-center gap-3">
                            <h1 className="text-3xl font-semibold tracking-tight">
                                {t('Save fix request')}
                            </h1>
                            <Badge variant="secondary">{fixRequest.status_label}</Badge>
                        </div>
                    </div>
                </div>

                <div className="flex flex-col gap-6">
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base font-semibold">{t('Details')}</CardTitle>
                            <CardDescription>
                                {fixRequest.assignee
                                    ? t('Assigned to :username', {
                                          username: fixRequest.assignee.username,
                                      })
                                    : t('Waiting for a staff member to claim this request.')}
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="flex flex-col gap-4">
                            <p className="whitespace-pre-wrap text-sm text-foreground">
                                {fixRequest.description}
                            </p>
                            <p className="text-xs text-muted-foreground">{consentText}</p>
                            {fixRequest.can_cancel && (
                                <Form {...cancelFixRequest.form(fixRequest.uuid)}>
                                    {({ processing }) => (
                                        <Button
                                            type="submit"
                                            variant="outline"
                                            disabled={processing}
                                            onClick={(event) => {
                                                if (!confirm(t('Cancel this save fix request?'))) {
                                                    event.preventDefault();
                                                }
                                            }}
                                        >
                                            {t('Cancel request')}
                                        </Button>
                                    )}
                                </Form>
                            )}
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base font-semibold">
                                {t('Notifications')}
                            </CardTitle>
                            <CardDescription>
                                {t('Choose how you want updates about this request.')}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Form
                                {...updateNotifications.form(fixRequest.uuid)}
                                className="flex flex-col gap-4"
                            >
                                {({ processing }) => (
                                    <>
                                        <label className="flex items-center gap-2 text-sm">
                                            <input
                                                type="checkbox"
                                                name="notify_database"
                                                value="1"
                                                defaultChecked={fixRequest.notify_database}
                                            />
                                            <span>{t('In-app notifications')}</span>
                                        </label>
                                        <label className="flex items-center gap-2 text-sm">
                                            <input
                                                type="checkbox"
                                                name="notify_mail"
                                                value="1"
                                                defaultChecked={fixRequest.notify_mail}
                                            />
                                            <span>{t('Email notifications')}</span>
                                        </label>
                                        <Button type="submit" disabled={processing}>
                                            {t('Save preferences')}
                                        </Button>
                                    </>
                                )}
                            </Form>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}
