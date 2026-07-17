import { Head, Link, router, usePage } from '@inertiajs/react';

import { create } from '@/actions/App/Http/Controllers/Skin/SkinController';
import {
    destroy as destroyPlayerSkin,
    duplicate,
} from '@/actions/App/Http/Controllers/Skin/PlayerSkinController';
import SkinCard, { type SkinCardData } from '@/components/skin-card';
import { Button } from '@/components/ui/button';
import type { SharedPageProps } from '@/types';

type Props = {
    skins: SkinCardData[];
    currentSkin: {
        exists: boolean;
        image_url: string | null;
    };
    slots: {
        used: number;
        max: number;
    };
    canImport: boolean;
    importUrl: string;
    templateUrl: string;
    deleteActivity: Array<{ created_at: string | null; reason: string | null }>;
    gamejoltId: number;
};

export default function SkinsHome({
    skins,
    currentSkin,
    slots,
    canImport,
    importUrl,
    templateUrl,
    deleteActivity,
}: Props) {
    const { auth } = usePage<SharedPageProps>().props;

    return (
        <>
            <Head title="Skins" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-6 text-sm text-slate-500 dark:text-slate-400">Skins</div>

                    <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
                        <div className="space-y-4">
                            <div className="overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900">
                                <div className="w-full p-4">
                                    <div className="mb-2 text-xl font-medium text-slate-800 dark:text-white">Current In-game Skin</div>
                                    {currentSkin.exists && currentSkin.image_url ? (
                                        <>
                                            <img
                                                src={currentSkin.image_url}
                                                className="mx-auto my-4 h-32 w-24 object-contain"
                                                alt={auth.user?.username ?? 'Current skin'}
                                            />
                                            <div className="mt-2 flex flex-wrap gap-2">
                                                <Button type="button" size="sm" onClick={() => router.post(duplicate.url())}>
                                                    Save to "My skins"
                                                </Button>
                                                <Button
                                                    type="button"
                                                    size="sm"
                                                    variant="destructive"
                                                    onClick={() => {
                                                        if (confirm('Delete your in-game skin?')) {
                                                            router.delete(destroyPlayerSkin.url());
                                                        }
                                                    }}
                                                >
                                                    Delete
                                                </Button>
                                            </div>
                                        </>
                                    ) : (
                                        <>
                                            <p className="dark:text-slate-400">No skins have been added to your account yet.</p>
                                            {! canImport ? (
                                                <div className="mt-4 rounded-lg bg-blue-300 px-4 py-3 text-sm leading-normal text-blue-800">
                                                    Your slots are full. You cannot import from the old site unless you delete one of the slots.
                                                </div>
                                            ) : (
                                                <Button
                                                    type="button"
                                                    className="mt-3 w-full"
                                                    variant="secondary"
                                                    onClick={() => router.post(importUrl)}
                                                >
                                                    Check for skin to import from the old site
                                                </Button>
                                            )}
                                        </>
                                    )}
                                </div>
                            </div>

                            <div className="overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900">
                                <div className="w-full p-4">
                                    <div className="mb-2 text-xl font-medium text-slate-800 dark:text-white">Admin Skin Deletion Activity</div>
                                    <div className="space-y-1 text-sm text-slate-800 dark:text-white">
                                        {deleteActivity.length > 0 ? (
                                            deleteActivity.map((log, index) => (
                                                <p key={`${log.created_at}-${index}`} className="m-0">
                                                    <span className="text-slate-500">{log.created_at}:</span> {log.reason}
                                                </p>
                                            ))
                                        ) : (
                                            <p className="m-0">Nothing found.</p>
                                        )}
                                    </div>
                                </div>
                            </div>

                            <div className="overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900">
                                <div className="w-full p-4">
                                    <div className="mb-2 text-xl font-medium text-slate-800 dark:text-white">Skin Information</div>
                                    <div className="font-light text-slate-800 text-md dark:text-slate-300">
                                        <p>
                                            Want to make your own skin?{' '}
                                            <a href={templateUrl} className="text-green-500">
                                                Download this template
                                            </a>{' '}
                                            to get started.
                                        </p>
                                        <h6 className="mt-1 font-bold text-slate-700 dark:text-slate-200">File Validation</h6>
                                        <ul className="list-disc pl-6">
                                            <li>Less than 2MB</li>
                                            <li>Has to be a PNG-file</li>
                                            <li>Dimensions ratio of 3/4</li>
                                        </ul>
                                        <h6 className="mt-1 font-bold text-slate-700 dark:text-slate-200">Rules</h6>
                                        <ul className="list-disc pl-6">
                                            <li>Every part (for a 96x128 sprite, every 32x32 portion) of the skin has to contain at least one pixel that is not transparent.</li>
                                            <li>You have to own the rights to use the image you upload.</li>
                                            <li>The image must not contain any sexual or harassing content.</li>
                                        </ul>
                                        <p className="mt-2 text-sm text-slate-500">
                                            If all of the above rules apply to your skin and you upload it, you transfer all rights to the P3D Team. We can alter and delete your skin as long as it stays on our servers.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="lg:col-span-2">
                            <div className="mb-6 flex items-center justify-between rounded-lg bg-white px-6 py-4 shadow-md dark:bg-slate-900">
                                <span className="font-semibold text-slate-900 dark:text-slate-200">
                                    Slots: {slots.used} / {slots.max}
                                </span>
                                <Link
                                    href={create.url()}
                                    className="rounded bg-green-500 px-2 py-1 text-sm font-bold text-white hover:bg-green-700"
                                >
                                    Create
                                </Link>
                            </div>

                            <div className="grid auto-rows-max grid-cols-1 gap-4 sm:grid-cols-2">
                                {skins.map((skin) => (
                                    <SkinCard key={skin.uuid} skin={skin} authenticated />
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
