import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, HardDrivesIcon } from '@phosphor-icons/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { index, store } from '@/routes/server';

export default function ServersCreate() {
    return (
        <>
            <Head title="Add Server" />

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
                        <h1 className="text-3xl font-semibold tracking-tight">Add server</h1>
                        <p className="text-sm text-muted-foreground">
                            List a community server so other trainers can find and join it.
                        </p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">Server details</CardTitle>
                        <CardDescription>
                            Use a public host and port that players can reach. Names cannot contain "official".
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form {...store.form()} className="flex flex-col gap-4">
                            {({ processing, errors }) => (
                                <>
                                    <Field id="name" label="Name" error={errors.name} placeholder="My adventure world" />
                                    <Field id="host" label="Host" error={errors.host} placeholder="play.example.com" />
                                    <Field
                                        id="port"
                                        label="Port"
                                        type="number"
                                        error={errors.port}
                                        placeholder="40000"
                                    />
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="description">Description</Label>
                                        <Textarea
                                            id="description"
                                            name="description"
                                            placeholder="What kind of experience can players expect?"
                                            className="min-h-28"
                                        />
                                        <InputError message={errors.description} />
                                    </div>
                                    <div className="flex flex-wrap justify-end gap-2">
                                        <Button type="button" variant="outline" asChild>
                                            <Link href={index.url()}>Cancel</Link>
                                        </Button>
                                        <Button type="submit" disabled={processing}>
                                            Create server
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

function Field({
    id,
    label,
    type = 'text',
    error,
    placeholder,
}: {
    id: string;
    label: string;
    type?: string;
    error?: string;
    placeholder?: string;
}) {
    return (
        <div className="flex flex-col gap-2">
            <Label htmlFor={id}>{label}</Label>
            <Input id={id} name={id} type={type} required={id !== 'description'} placeholder={placeholder} />
            <InputError message={error} />
        </div>
    );
}
