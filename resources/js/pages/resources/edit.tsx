import { Form, Head, Link } from '@inertiajs/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as resourceIndex, update, uuid as resourceShow } from '@/routes/resource';

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

export default function ResourceEdit({ resource, categories, copy }: Props) {
    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <nav className="mb-6 text-sm text-slate-500">
                    <Link href={resourceIndex.url()} className="hover:underline">
                        {copy.resources}
                    </Link>
                    <span className="mx-2">/</span>
                    <Link href={resourceShow.url(resource.uuid)} className="hover:underline">
                        {resource.name}
                    </Link>
                    <span className="mx-2">/</span>
                    <span className="text-slate-700 dark:text-slate-300">{copy.title}</span>
                </nav>

                <div className="mx-auto max-w-4xl">
                    <div className="rounded-lg bg-white px-6 py-6 shadow-md dark:bg-slate-900">
                        <div className="mb-6 flex items-center justify-between">
                            <h1 className="text-2xl font-bold text-slate-900 dark:text-white">{copy.title}</h1>
                            <Link href={resourceShow.url(resource.uuid)} className="text-slate-400 hover:text-slate-600">
                                {copy.cancel}
                            </Link>
                        </div>

                        <Form {...update.form(resource.uuid)} className="space-y-4">
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="name">{copy.name}</Label>
                                        <Input id="name" name="name" required autoFocus defaultValue={resource.name} />
                                        <InputError message={errors.name} />
                                    </div>

                                    <div className="grid gap-2">
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

                                    <div className="grid gap-2">
                                        <Label htmlFor="category">{copy.category}</Label>
                                        <select
                                            id="category"
                                            name="category"
                                            required
                                            className="h-10 w-full rounded-lg border border-slate-300 bg-white px-3 text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
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

                                    <div className="grid gap-2">
                                        <Label htmlFor="description">{copy.description}</Label>
                                        <textarea
                                            id="description"
                                            name="description"
                                            required
                                            rows={10}
                                            defaultValue={resource.description}
                                            className="w-full rounded-md border border-slate-300 bg-transparent px-3 py-2 text-sm dark:border-slate-600 dark:text-white"
                                        />
                                        <InputError message={errors.description} />
                                    </div>

                                    <div className="flex justify-end gap-3">
                                        <Button asChild variant="outline">
                                            <Link href={resourceShow.url(resource.uuid)}>{copy.cancel}</Link>
                                        </Button>
                                        <Button type="submit" variant="brand" disabled={processing}>
                                            {copy.submit}
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </div>
                </div>
            </div>
        </>
    );
}
