import { Head, Link } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { edit, index } from '@/routes/tags';

type Props = {
    tag: { id: number; name: string };
};

export default function TagsShow({ tag }: Props) {
    return (
        <>
            <Head title={tag.name} />

            <div className="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
                <h1 className="mb-2 text-xl font-semibold">{tag.name}</h1>
                <p className="mb-6 text-sm text-slate-500">ID: {tag.id}</p>
                <div className="flex gap-3">
                    <Link href={edit.url(tag.id)}>
                        <Button variant="default">Edit</Button>
                    </Link>
                    <Link href={index.url()}>
                        <Button variant="outline">Back</Button>
                    </Link>
                </div>
            </div>
        </>
    );
}
