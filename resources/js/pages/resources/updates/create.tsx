import { Form, Head, Link } from '@inertiajs/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as resourceIndex, uuid as resourceShow } from '@/routes/resource';
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

export default function ResourceUpdateCreate({ resource, gameVersions, copy }: Props) {
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
                        <h1 className="mb-6 text-2xl font-bold text-slate-900 dark:text-white">{copy.title}</h1>

                        <Form {...storeUpdate.form(resource.uuid)} encType="multipart/form-data" className="space-y-4">
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="version">{copy.versionTitle}</Label>
                                        <Input id="version" name="version" required autoFocus placeholder="1.2.3" />
                                        <InputError message={errors.version} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="gameversion">{copy.gameVersion}</Label>
                                        <select
                                            id="gameversion"
                                            name="gameversion"
                                            required
                                            className="h-10 w-full rounded-lg border border-slate-300 bg-white px-3 text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
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

                                    <div className="grid gap-2">
                                        <Label htmlFor="description">{copy.description}</Label>
                                        <textarea
                                            id="description"
                                            name="description"
                                            required
                                            rows={8}
                                            className="w-full rounded-md border border-slate-300 bg-transparent px-3 py-2 text-sm dark:border-slate-600 dark:text-white"
                                        />
                                        <InputError message={errors.description} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="file">{copy.file}</Label>
                                        <Input id="file" name="file" type="file" accept=".zip,application/zip" required />
                                        <InputError message={errors.file} />
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
