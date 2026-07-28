import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, BooksIcon, StarIcon } from '@phosphor-icons/react';
import { useState } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import { uuid as resourceShow } from '@/routes/resource';
import { store as storeRating } from '@/routes/resource/rate';

type Props = {
    resource: {
        uuid: string;
        name: string;
    };
    copy: {
        resources: string;
        title: string;
        clickToRate: string;
        reviewLabel: string;
        reviewPlaceholder: string;
        minMax: string;
        cancel: string;
        submit: string;
        labels: Record<number, string>;
    };
};

export default function ResourceRate({ resource, copy }: Props) {
    const { t } = useTranslations();
    const [rating, setRating] = useState(0);
    const [body, setBody] = useState('');

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
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <BooksIcon className="size-5" weight="fill" />
                            <span className="text-sm">{copy.resources}</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                        <p className="text-sm text-muted-foreground">
                            {t('Rate :name and share a short review.', { name: resource.name })}
                        </p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">{t('Your rating')}</CardTitle>
                        <CardDescription>{copy.clickToRate}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form {...storeRating.form(resource.uuid)} className="flex flex-col gap-6">
                            {({ processing, errors }) => (
                                <>
                                    <div className="flex flex-col items-center gap-3 border border-border bg-muted/20 px-4 py-6">
                                        <div className="flex items-center gap-1">
                                            {[1, 2, 3, 4, 5].map((value) => (
                                                <button
                                                    key={value}
                                                    type="button"
                                                    title={copy.labels[value]}
                                                    onClick={() => setRating(value)}
                                                    className={cn(
                                                        'rounded-none p-1 transition-colors',
                                                        rating >= value
                                                            ? 'text-amber-500'
                                                            : 'text-muted-foreground/40 hover:text-amber-500/70',
                                                    )}
                                                >
                                                    <StarIcon className="size-8" weight="fill" />
                                                </button>
                                            ))}
                                        </div>
                                        <p className="text-sm text-muted-foreground">
                                            {rating > 0 ? copy.labels[rating] : copy.clickToRate}
                                        </p>
                                        <input type="hidden" name="rating" value={rating || ''} />
                                        <InputError message={errors.rating} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="body">{copy.reviewLabel}</Label>
                                        <Textarea
                                            id="body"
                                            name="body"
                                            required
                                            minLength={10}
                                            maxLength={255}
                                            placeholder={copy.reviewPlaceholder}
                                            value={body}
                                            onChange={(event) => setBody(event.target.value)}
                                            className="min-h-28"
                                        />
                                        <div className="flex justify-between gap-2 text-xs text-muted-foreground">
                                            <span>{copy.minMax}</span>
                                            <span>{body.length}/255</span>
                                        </div>
                                        <InputError message={errors.body} />
                                    </div>

                                    <div className="flex flex-wrap justify-end gap-2">
                                        <Button type="button" variant="outline" asChild>
                                            <Link href={resourceShow.url(resource.uuid)}>{copy.cancel}</Link>
                                        </Button>
                                        <Button type="submit" disabled={processing || rating < 1}>
                                            {copy.submit}
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
