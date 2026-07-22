import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, HardDrivesIcon } from '@phosphor-icons/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
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

            <div className="mx-auto w-full max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={index.url()}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            Back to servers
                        </Link>
                    </Button>
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <HardDrivesIcon className="size-5" weight="fill" />
                            <span className="text-sm">Multiplayer</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">Edit server</h1>
                        <p className="text-sm text-muted-foreground">
                            Update the listing details for <span className="font-medium text-foreground">{server.name}</span>.
                        </p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">Server details</CardTitle>
                        <CardDescription>
                            Changes appear on the public servers list once saved.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form {...update.form(server.uuid)} className="flex flex-col gap-4">
                            {({ processing, errors }) => (
                                <>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="name">Name</Label>
                                        <Input id="name" name="name" defaultValue={server.name} required />
                                        <InputError message={errors.name} />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="host">Host</Label>
                                        <Input id="host" name="host" defaultValue={server.host} required />
                                        <InputError message={errors.host} />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="port">Port</Label>
                                        <Input
                                            id="port"
                                            name="port"
                                            type="number"
                                            defaultValue={server.port}
                                            required
                                        />
                                        <InputError message={errors.port} />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="description">Description</Label>
                                        <Textarea
                                            id="description"
                                            name="description"
                                            defaultValue={server.description ?? ''}
                                            className="min-h-28"
                                        />
                                        <InputError message={errors.description} />
                                    </div>
                                    <div className="flex flex-wrap justify-end gap-2">
                                        <Button type="button" variant="outline" asChild>
                                            <Link href={index.url()}>Cancel</Link>
                                        </Button>
                                        <Button type="submit" disabled={processing}>
                                            Save changes
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
