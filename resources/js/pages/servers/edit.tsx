import { Form, Head, Link } from '@inertiajs/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index, update } from '@/routes/server';

type Props = {
    server: {
        uuid: string;
        name: string;
        host: string;
        port: number;
        description: string | null;
    };
};

export default function ServersEdit({ server }: Props) {
    return (
        <>
            <Head title={`Edit ${server.name}`} />

            <div className="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
                <h1 className="mb-6 text-xl font-semibold">Edit Server</h1>
                <Form {...update.form(server.uuid)} className="space-y-4 rounded-lg bg-white p-6 shadow dark:bg-slate-900">
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Name</Label>
                                <Input id="name" name="name" defaultValue={server.name} required />
                                <InputError message={errors.name} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="host">Host</Label>
                                <Input id="host" name="host" defaultValue={server.host} required />
                                <InputError message={errors.host} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="port">Port</Label>
                                <Input id="port" name="port" type="number" defaultValue={server.port} required />
                                <InputError message={errors.port} />
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="description">Description</Label>
                                <textarea
                                    id="description"
                                    name="description"
                                    defaultValue={server.description ?? ''}
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
