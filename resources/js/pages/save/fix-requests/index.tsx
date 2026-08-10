import { Head, Link } from '@inertiajs/react';
import { FloppyDiskIcon } from '@phosphor-icons/react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { useTranslations } from '@/hooks/use-translations';
import { index as saveIndex } from '@/routes/save';
import {
    create as createFixRequest,
    show as showFixRequest,
} from '@/routes/save/fix-requests';
import type { Paginated } from '@/types';

type FixRequest = {
    uuid: string;
    description: string;
    status: string;
    status_label: string;
    created_at: string | null;
};

type Props = {
    requests: Paginated<FixRequest>;
    canCreate: boolean;
};

export default function SaveFixRequestsIndex({ requests, canCreate }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Save fix requests')} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={saveIndex.url()}>{t('Back to My Save')}</Link>
                    </Button>
                    <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div className="flex flex-col gap-2">
                            <div className="flex items-center gap-2 text-muted-foreground">
                                <FloppyDiskIcon className="size-5" weight="fill" />
                                <span className="text-sm">{t('Game')}</span>
                            </div>
                            <h1 className="text-3xl font-semibold tracking-tight">
                                {t('Save fix requests')}
                            </h1>
                            <p className="text-sm text-muted-foreground">
                                {t('Ask staff for help if your game save is stuck or broken.')}
                            </p>
                        </div>
                        {canCreate && (
                            <Button asChild>
                                <Link href={createFixRequest.url()}>{t('New request')}</Link>
                            </Button>
                        )}
                    </div>
                </div>

                <Card>
                    <CardContent className="flex flex-col gap-4 p-5">
                        {requests.data.length === 0 ? (
                            <p className="text-sm text-muted-foreground">
                                {t('You have not opened any save fix requests yet.')}
                            </p>
                        ) : (
                            requests.data.map((request) => (
                                <Link
                                    key={request.uuid}
                                    href={showFixRequest.url(request.uuid)}
                                    className="flex flex-col gap-2 border-b border-border pb-4 last:border-b-0 last:pb-0"
                                >
                                    <div className="flex items-center justify-between gap-3">
                                        <Badge variant="secondary">{request.status_label}</Badge>
                                        <span className="text-xs text-muted-foreground">
                                            {request.created_at
                                                ? new Date(request.created_at).toLocaleString()
                                                : ''}
                                        </span>
                                    </div>
                                    <p className="line-clamp-2 text-sm text-foreground">
                                        {request.description}
                                    </p>
                                </Link>
                            ))
                        )}
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
