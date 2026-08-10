import { Form, Head, Link } from '@inertiajs/react';
import { FloppyDiskIcon } from '@phosphor-icons/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useTranslations } from '@/hooks/use-translations';
import {
    index as fixRequestsIndex,
    store as storeFixRequest,
} from '@/routes/save/fix-requests';

type Props = {
    consentText: string;
};

export default function SaveFixRequestCreate({ consentText }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('New save fix request')} />

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
                        <h1 className="text-3xl font-semibold tracking-tight">
                            {t('New save fix request')}
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            {t('Describe the problem. Staff can view your synced save after you consent.')}
                        </p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">{t('Request details')}</CardTitle>
                        <CardDescription>
                            {t('Include where you are stuck and what you already tried.')}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form {...storeFixRequest.form()} className="flex flex-col gap-4">
                            {({ processing, errors }) => (
                                <>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="description">{t('Description')}</Label>
                                        <Textarea
                                            id="description"
                                            name="description"
                                            required
                                            rows={6}
                                            autoFocus
                                        />
                                        <InputError message={errors.description} />
                                    </div>

                                    <label className="flex items-start gap-2 text-sm">
                                        <input
                                            type="checkbox"
                                            name="consent_accepted"
                                            value="1"
                                            required
                                            className="mt-1"
                                        />
                                        <span>{consentText}</span>
                                    </label>
                                    <InputError message={errors.consent_accepted} />

                                    <div className="flex flex-col gap-2 border-t border-border pt-4">
                                        <p className="text-sm font-medium">{t('Notifications')}</p>
                                        <label className="flex items-center gap-2 text-sm">
                                            <input
                                                type="checkbox"
                                                name="notify_database"
                                                value="1"
                                                defaultChecked
                                            />
                                            <span>{t('In-app notifications')}</span>
                                        </label>
                                        <label className="flex items-center gap-2 text-sm">
                                            <input
                                                type="checkbox"
                                                name="notify_mail"
                                                value="1"
                                                defaultChecked
                                            />
                                            <span>{t('Email notifications')}</span>
                                        </label>
                                    </div>

                                    <Button type="submit" disabled={processing}>
                                        {t('Submit request')}
                                    </Button>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
