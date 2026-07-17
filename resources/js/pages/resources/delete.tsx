import { Form, Head, Link } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { destroy, index as resourceIndex, uuid as resourceShow } from '@/routes/resource';

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

                <div className="mx-auto max-w-2xl">
                    <div className="rounded-lg border border-red-200 bg-red-50 p-6 dark:border-red-800 dark:bg-red-900/10">
                        <h1 className="mb-4 text-lg font-semibold text-red-900 dark:text-red-100">{copy.title}</h1>
                        <p className="mb-4 text-red-800 dark:text-red-200">{copy.confirm}</p>
                        <div className="mb-6 rounded-lg border border-red-200 bg-red-100 p-4 dark:border-red-800 dark:bg-red-900/20">
                            <p className="font-semibold text-red-900 dark:text-red-100">{resource.name}</p>
                            <p className="mt-1 text-sm text-red-700 dark:text-red-300">{resource.brief}</p>
                        </div>
                        <div className="mb-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-900/10">
                            <p className="text-sm font-medium text-yellow-800 dark:text-yellow-200">{copy.warning}</p>
                            <p className="mt-1 text-sm text-yellow-700 dark:text-yellow-300">{copy.warningBody}</p>
                        </div>
                    </div>

                    <div className="mt-6 flex justify-end gap-3">
                        <Button asChild variant="outline">
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
                </div>
            </div>
        </>
    );
}
