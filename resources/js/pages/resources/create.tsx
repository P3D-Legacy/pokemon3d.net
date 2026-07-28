import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, BooksIcon } from '@phosphor-icons/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useTranslations } from '@/hooks/use-translations';
import { index as resourceIndex, store } from '@/routes/resource';

type Props = {
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
    };
};

const selectClassName =
    'border-input h-8 w-full rounded-none border bg-transparent px-2.5 text-xs outline-none focus-visible:border-ring focus-visible:ring-1 focus-visible:ring-ring/50 dark:bg-input/30';

export default function ResourceCreate({ categories, copy }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={resourceIndex.url()}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            {t('Back to :page', { page: copy.resources.toLowerCase() })}
                        </Link>
                    </Button>
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <BooksIcon className="size-5" weight="fill" />
                            <span className="text-sm">{copy.resources}</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                        <p className="text-sm text-muted-foreground">
                            {t('Share a mod, tool, or other resource with the community.')}
                        </p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">{t('Resource details')}</CardTitle>
                        <CardDescription>
                            {t(
                                'Add a clear name, short summary, and full description so others know what to expect.',
                            )}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form {...store.form()} className="flex flex-col gap-4">
                            {({ processing, errors }) => (
                                <>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="name">{copy.name}</Label>
                                        <Input id="name" name="name" required autoFocus />
                                        <InputError message={errors.name} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="brief">{copy.brief}</Label>
                                        <Input
                                            id="brief"
                                            name="brief"
                                            required
                                            placeholder={copy.briefPlaceholder}
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
                                            defaultValue=""
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
                                        <Textarea id="description" name="description" required className="min-h-40" />
                                        <InputError message={errors.description} />
                                    </div>

                                    <div className="flex flex-wrap justify-end gap-2">
                                        <Button type="button" variant="outline" asChild>
                                            <Link href={resourceIndex.url()}>{t('Cancel')}</Link>
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
