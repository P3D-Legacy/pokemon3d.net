import { Form, Head } from '@inertiajs/react';

import { update } from '@/actions/App/Http/Controllers/Skin/SkinController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Props = {
    skin: {
        uuid: string;
        name: string;
        public: boolean;
    };
};

export default function SkinsEdit({ skin }: Props) {
    return (
        <>
            <Head title={`Edit: ${skin.name}`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-6 text-sm text-slate-500 dark:text-slate-400">Skins / Edit / {skin.name}</div>

                    <div className="overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900">
                        <div className="w-full p-4">
                            <Form {...update.form(skin.uuid)} className="space-y-4">
                                {({ processing, errors }) => (
                                    <>
                                        <div>
                                            <Label htmlFor="name">Name</Label>
                                            <Input id="name" name="name" type="text" className="mt-1" defaultValue={skin.name} autoComplete="name" required />
                                            <InputError message={errors.name} className="mt-2" />
                                        </div>
                                        <div>
                                            <label htmlFor="public" className="flex items-center gap-2">
                                                <input
                                                    id="public"
                                                    name="public"
                                                    type="checkbox"
                                                    value="1"
                                                    defaultChecked={skin.public}
                                                    className="rounded border-slate-300 text-green-600 shadow-sm"
                                                />
                                                <span className="text-slate-700 dark:text-slate-300">
                                                    Public <span className="text-sm text-slate-500">Other users will be able to see this skin</span>
                                                </span>
                                            </label>
                                            <InputError message={errors.public} className="mt-2" />
                                        </div>
                                        <Button type="submit" variant="brand" disabled={processing}>
                                            Save
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
