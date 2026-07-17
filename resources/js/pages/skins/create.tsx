import { Form, Head, Link } from '@inertiajs/react';

import { store } from '@/actions/App/Http/Controllers/Skin/SkinController';
import { index as skinHome } from '@/actions/App/Http/Controllers/Skin/SkinHomeController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function SkinsCreate() {
    return (
        <>
            <Head title="Create Skin" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-6 text-sm text-slate-500 dark:text-slate-400">Skins / Create</div>

                    <div className="overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900">
                        <div className="w-full p-4">
                            <Form {...store.form()} encType="multipart/form-data" className="space-y-4">
                                {({ processing, errors }) => (
                                    <>
                                        <div>
                                            <Label htmlFor="name">Name</Label>
                                            <Input id="name" name="name" type="text" className="mt-1" autoComplete="name" required />
                                            <InputError message={errors.name} className="mt-2" />
                                        </div>
                                        <div>
                                            <Label htmlFor="image">Select image file</Label>
                                            <Input
                                                id="image"
                                                name="image"
                                                type="file"
                                                accept="image/png"
                                                className="mt-1 file:mr-4 file:rounded file:border-0 file:bg-green-500 file:px-2 file:py-1 file:text-sm file:font-semibold file:text-green-50 hover:file:bg-green-800"
                                                required
                                            />
                                            <InputError message={errors.image} className="mt-2" />
                                        </div>
                                        <div>
                                            <label htmlFor="public" className="flex items-center gap-2">
                                                <input id="public" name="public" type="checkbox" value="1" className="rounded border-slate-300 text-green-600 shadow-sm" />
                                                <span className="text-sm text-slate-600 dark:text-slate-300">
                                                    Public <span className="text-slate-500">Other users will be able to see this skin</span>
                                                </span>
                                            </label>
                                            <InputError message={errors.public} className="mt-2" />
                                        </div>
                                        <div>
                                            <label htmlFor="rules" className="flex items-center gap-2">
                                                <input id="rules" name="rules" type="checkbox" value="1" className="rounded border-slate-300 text-green-600 shadow-sm" required />
                                                <span className="text-sm text-slate-600 dark:text-slate-300">
                                                    <strong>I accept and understand the rules</strong> for uploading a custom skin.{' '}
                                                    <span className="text-slate-500">
                                                        Read the rules on the{' '}
                                                        <Link href={skinHome.url()} className="text-green-500">
                                                            skin home page
                                                        </Link>
                                                        .
                                                    </span>
                                                </span>
                                            </label>
                                            <InputError message={errors.rules} className="mt-2" />
                                        </div>
                                        <Button type="submit" variant="brand" disabled={processing}>
                                            Upload
                                        </Button>
                                    </>
                                )}
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
