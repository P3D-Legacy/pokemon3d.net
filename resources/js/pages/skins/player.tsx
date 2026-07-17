import { Form, Head } from '@inertiajs/react';
import { useState } from 'react';

import { destroyAsAdmin } from '@/actions/App/Http/Controllers/Skin/PlayerSkinController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

type PlayerSkin = {
    filename: string;
    gjid: number;
    owner_label: string;
    image_url: string;
    file_size: string;
};

type Props = {
    playerSkins: PlayerSkin[];
};

export default function SkinsPlayer({ playerSkins }: Props) {
    const [reasons, setReasons] = useState<Record<number, string>>({});

    return (
        <>
            <Head title="Player Skins" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h2 className="mb-4 border-b-2 border-slate-200 pb-1 text-3xl font-extrabold leading-9 text-slate-800 dark:border-slate-700 dark:text-slate-50">
                        Player Skins
                    </h2>

                    <div className="grid auto-rows-max grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {playerSkins.map((playerSkin) => (
                            <div key={playerSkin.filename} className="flex max-w-md overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900">
                                <div className="w-1/4 items-center justify-center pt-4 pl-4">
                                    <img className="mx-auto h-32 w-24 object-contain" src={playerSkin.image_url} alt={playerSkin.filename} width={96} height={128} />
                                </div>
                                <div className="w-3/4 p-4">
                                    <h1 className="text-2xl font-bold text-slate-900 dark:text-slate-100">{playerSkin.filename}</h1>
                                    <p className="mt-2 text-xs text-slate-600 dark:text-slate-300">
                                        Owned by: {playerSkin.owner_label}
                                        <br />
                                        File size: {playerSkin.file_size}
                                    </p>
                                    <Form {...destroyAsAdmin.form(playerSkin.gjid)} className="mt-2 w-full">
                                        {({ processing }) => (
                                            <>
                                                <p className="my-2 m-0 text-xs text-blue-500">
                                                    Users will be able to see the reason for the deletion!
                                                </p>
                                                <Input
                                                    name="reason"
                                                    value={reasons[playerSkin.gjid] ?? ''}
                                                    onChange={(event) =>
                                                        setReasons((current) => ({
                                                            ...current,
                                                            [playerSkin.gjid]: event.target.value,
                                                        }))
                                                    }
                                                    placeholder="Add a legit reason here"
                                                    required
                                                />
                                                <Button type="submit" variant="destructive" size="sm" className="mt-2" disabled={processing}>
                                                    Delete
                                                </Button>
                                            </>
                                        )}
                                    </Form>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </>
    );
}
