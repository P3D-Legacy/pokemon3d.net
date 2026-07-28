import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, BooksIcon } from '@phosphor-icons/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useTranslations } from '@/hooks/use-translations';
import { update, uuid as resourceShow } from '@/routes/resource';

type Props = {
    resource: {
        uuid: string;
        name: string;
        brief: string;
        description: string;
        category_id: number | null;
    };
    categories: Array<{ id: number; name: string }>;
    copy: {
        title: string;
        resources: string;
        name: string;
        brief: string;
        briefPlaceholder: string;
        category: string;
        selectCategory: string;
        description: string;
        submit: string;
        cancel: string;
    };
};

const selectClassName =
    'border-input h-8 w-full rounded-none border bg-transparent px-2.5 text-xs outline-none focus-visible:border-ring focus-visible:ring-1 focus-visible:ring-ring/50 dark:bg-input/30';

export default function ResourceEdit({ resource, categories, copy }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
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
                            {t('Update the listing for :name.', { name: resource.name })}
                        </p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">{t('Resource details')}</CardTitle>
                        <CardDescription>
                            {t('Changes are visible on the resource page once saved.')}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form {...update.form(resource.uuid)} className="flex flex-col gap-4">
                            {({ processing, errors }) => (
                                <>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="name">{copy.name}</Label>
                                        <Input
                                            id="name"
                                            name="name"
                                            required
                                            autoFocus
                                            defaultValue={resource.name}
                                        />
                                        <InputError message={errors.name} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="brief">{copy.brief}</Label>
                                        <Input
                                            id="brief"
                                            name="brief"
                                            required
                                            placeholder={copy.briefPlaceholder}
                                            defaultValue={resource.brief}
                                        />
                                        <InputError message={errors.brief} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="category">{copy.category}</Label>
                                        <select
                                            id="category"
                                            name="category"
                                            required
                                            className={selectClassName}
                                            defaultValue={resource.category_id ?? ''}
                                        >
                                            <option value="" disabled>
                                                {copy.selectCategory}
                                            </option>
                                            {categories.map((category) => (
                                                <option key={category.id} value={category.id}>
                                                    {category.name}
                                                </option>
                                            ))}
                                        </select>
                                        <InputError message={errors.category} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="description">{copy.description}</Label>
                                        <Textarea
                                            id="description"
                                            name="description"
                                            required
                                            defaultValue={resource.description}
                                            className="min-h-40"
                                        />
                                        <InputError message={errors.description} />
                                    </div>

                                    <div className="flex flex-wrap justify-end gap-2">
                                        <Button type="button" variant="outline" asChild>
                                            <Link href={resourceShow.url(resource.uuid)}>{copy.cancel}</Link>
                                        </Button>
                                        <Button type="submit" disabled={processing}>
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
