import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, BooksIcon } from '@phosphor-icons/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { uuid as resourceShow } from '@/routes/resource';
import { store as storeUpdate } from '@/routes/resource/updates';

type Props = {
    resource: {
        uuid: string;
        name: string;
    };
    gameVersions: Array<{ id: number; version: string }>;
    copy: {
        resources: string;
        title: string;
        versionTitle: string;
        gameVersion: string;
        selectGameVersion: string;
        description: string;
        file: string;
        cancel: string;
        submit: string;
    };
};

const selectClassName =
    'border-input h-8 w-full rounded-none border bg-transparent px-2.5 text-xs outline-none focus-visible:border-ring focus-visible:ring-1 focus-visible:ring-ring/50 dark:bg-input/30';

export default function ResourceUpdateCreate({ resource, gameVersions, copy }: Props) {
    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={resourceShow.url(resource.uuid)}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            Back to {resource.name}
                        </Link>
                    </Button>
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <BooksIcon className="size-5" weight="fill" />
                            <span className="text-sm">{copy.resources}</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                        <p className="text-sm text-muted-foreground">
                            Publish a new update for{' '}
                            <span className="font-medium text-foreground">{resource.name}</span>.
                        </p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">Update details</CardTitle>
                        <CardDescription>
                            Include a version number, compatible game version, changelog, and zip file.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form
                            {...storeUpdate.form(resource.uuid)}
                            encType="multipart/form-data"
                            className="flex flex-col gap-4"
                        >
                            {({ processing, errors }) => (
                                <>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="version">{copy.versionTitle}</Label>
                                        <Input
                                            id="version"
                                            name="version"
                                            required
                                            autoFocus
                                            placeholder="1.2.3"
                                        />
                                        <InputError message={errors.version} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="gameversion">{copy.gameVersion}</Label>
                                        <select
                                            id="gameversion"
                                            name="gameversion"
                                            required
                                            className={selectClassName}
                                            defaultValue=""
                                        >
                                            <option value="" disabled>
                                                {copy.selectGameVersion}
                                            </option>
                                            {gameVersions.map((version) => (
                                                <option key={version.id} value={version.id}>
                                                    {version.version}
                                                </option>
                                            ))}
                                        </select>
                                        <InputError message={errors.gameversion} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="description">{copy.description}</Label>
                                        <Textarea
                                            id="description"
                                            name="description"
                                            required
                                            className="min-h-36"
                                        />
                                        <InputError message={errors.description} />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="file">{copy.file}</Label>
                                        <Input
                                            id="file"
                                            name="file"
                                            type="file"
                                            accept=".zip,application/zip"
                                            required
                                        />
                                        <InputError message={errors.file} />
                                    </div>

                                    <div className="flex flex-wrap justify-end gap-2">
                                        <Button type="button" variant="outline" asChild>
                                            <Link href={resourceShow.url(resource.uuid)}>{copy.cancel}</Link>
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
