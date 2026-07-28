import { Form, Head, Link } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import { create, destroy, edit, show } from '@/routes/tags';
import type { Paginated } from '@/types';

type Props = {
    tags: Paginated<{ id: number; name: string }>;
};

export default function TagsIndex({ tags }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Tags')} />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-xl font-semibold">{t('Tags')}</h1>
                    <Link href={create.url()}>
                        <Button variant="default">{t('Create')}</Button>
                    </Link>
                </div>

                <div className="overflow-hidden rounded-lg border border-slate-200 bg-white shadow dark:border-slate-800 dark:bg-black">
                    <table className="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                        <thead className="bg-slate-50 dark:bg-slate-950">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium uppercase text-slate-500">
                                    {t('ID')}
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium uppercase text-slate-500">
                                    {t('Name')}
                                </th>
                                <th className="px-6 py-3" />
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-200 dark:divide-slate-800">
                            {tags.data.map((tag) => (
                                <tr key={tag.id}>
                                    <td className="px-6 py-4 text-sm">{tag.id}</td>
                                    <td className="px-6 py-4 text-sm">{tag.name}</td>
                                    <td className="space-x-2 px-6 py-4 text-right text-sm">
                                        <Link href={show.url(tag.id)} className="text-blue-600 hover:underline">
                                            {t('View')}
                                        </Link>
                                        <Link href={edit.url(tag.id)} className="text-yellow-600 hover:underline">
                                            {t('Edit')}
                                        </Link>
                                        <Form {...destroy.form(tag.id)} className="inline">
                                            {({ processing }) => (
                                                <button
                                                    type="submit"
                                                    disabled={processing}
                                                    className="text-red-600 hover:underline"
                                                    onClick={(event) => {
                                                        if (! confirm(t('Are you sure?'))) {
                                                            event.preventDefault();
                                                        }
                                                    }}
                                                >
                                                    {t('Delete')}
                                                </button>
                                            )}
                                        </Form>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}
