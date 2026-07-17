import { Form, Head, Link } from '@inertiajs/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index, store } from '@/routes/server';

export default function ServersCreate() {
    return (
        <>
            <Head title="Add Server" />

            <div className="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
                <h1 className="mb-6 text-xl font-semibold">Add Server</h1>
                <Form {...store.form()} className="space-y-4 rounded-lg bg-white p-6 shadow dark:bg-slate-900">
                    {({ processing, errors }) => (
                        <>
                            <Field id="name" label="Name" error={errors.name} />
                            <Field id="host" label="Host" error={errors.host} />
                            <Field id="port" label="Port" type="number" error={errors.port} />
                            <div className="grid gap-2">
                                <Label htmlFor="description">Description</Label>
                                <textarea
                                    id="description"
                                    name="description"
                                    className="border-input min-h-24 rounded-md border bg-transparent px-3 py-2 text-sm"
                                />
                                <InputError message={errors.description} />
                            </div>
                            <div className="flex justify-end gap-3">
                                <Link href={index.url()}>
                                    <Button type="button" variant="outline">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" variant="brand" disabled={processing}>
                                    Create
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

function Field({
    id,
    label,
    type = 'text',
    error,
}: {
    id: string;
    label: string;
    type?: string;
    error?: string;
}) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={id}>{label}</Label>
            <Input id={id} name={id} type={type} required={id !== 'description'} />
            <InputError message={error} />
        </div>
    );
}
