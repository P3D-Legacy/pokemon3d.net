import { Form, Head, Link } from '@inertiajs/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index, update } from '@/routes/tags';

type Props = {
    tag: { id: number; name: string };
};

export default function TagsEdit({ tag }: Props) {
    return (
        <>
            <Head title="Edit Tag" />

            <div className="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
                <h1 className="mb-6 text-xl font-semibold">Edit Tag</h1>
                <Form {...update.form(tag.id)} className="space-y-4 rounded-lg bg-white p-6 shadow dark:bg-slate-900">
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Name</Label>
                                <Input id="name" name="name" defaultValue={tag.name} required />
                                <InputError message={errors.name} />
                            </div>
                            <div className="flex justify-end gap-3">
                                <Link href={index.url()}>
                                    <Button type="button" variant="outline">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" variant="default" disabled={processing}>
                                    Save
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
