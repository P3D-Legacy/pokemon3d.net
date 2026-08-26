import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, WarningIcon } from '@phosphor-icons/react';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { useTranslations } from '@/hooks/use-translations';
import { destroy, uuid as resourceShow } from '@/routes/resource';

type Props = {
    resource: {
        uuid: string;
        name: string;
        brief: string;
    };
    copy: {
        resources: string;
        title: string;
        confirm: string;
        warning: string;
        warningBody: string;
        cancel: string;
        submit: string;
    };
};

export default function ResourceDelete({ resource, copy }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto w-full max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={resourceShow.url(resource.uuid)}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            {t('Back to :name', { name: resource.name })}
                        </Link>
                    </Button>
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-destructive">
                            <WarningIcon className="size-5" weight="fill" />
                            <span className="text-sm">{copy.resources}</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                        <p className="text-sm text-muted-foreground">{copy.confirm}</p>
                    </div>
                </div>

                <Card className="border-destructive/40">
                    <CardHeader>
                        <CardTitle className="text-base font-semibold text-destructive">{resource.name}</CardTitle>
                        <CardDescription>{resource.brief}</CardDescription>
                    </CardHeader>
                    <CardContent className="flex flex-col gap-4">
                        <div className="border border-border bg-muted/30 px-4 py-3">
                            <p className="text-sm font-medium">{copy.warning}</p>
                            <p className="mt-1 text-sm text-muted-foreground">{copy.warningBody}</p>
                        </div>

                        <div className="flex flex-wrap justify-end gap-2">
                            <Button type="button" variant="outline" asChild>
                                <Link href={resourceShow.url(resource.uuid)}>{copy.cancel}</Link>
                            </Button>
                            <Form {...destroy.form(resource.uuid)}>
                                {({ processing }) => (
                                    <Button type="submit" variant="destructive" disabled={processing}>
                                        {copy.submit}
                                    </Button>
                                )}
                            </Form>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
