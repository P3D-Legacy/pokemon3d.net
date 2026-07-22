import { Head, Link, router, usePage } from '@inertiajs/react';
import {
    DownloadSimpleIcon,
    FloppyDiskIcon,
    PaintBrushIcon,
    PlusIcon,
    TrashIcon,
} from '@phosphor-icons/react';

import { create } from '@/actions/App/Http/Controllers/Skin/SkinController';
import {
    destroy as destroyPlayerSkin,
    duplicate,
} from '@/actions/App/Http/Controllers/Skin/PlayerSkinController';
import SkinCard from '@/components/skin-card';
import SkinImage from '@/components/skin-image';
import { Button } from '@/components/ui/button';
import type { SharedPageProps, SkinCardData } from '@/types';

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
    width: number;
    height: number;
};

export default function SkinsHome({
    skins,
    currentSkin,
    slots,
    canImport,
    importUrl,
    templateUrl,
    deleteActivity,
    width,
    height,
}: Props) {
    const { auth } = usePage<SharedPageProps>().props;

    return (
        <>
            <Head title="My Skins" />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-wrap items-end justify-between gap-4">
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <PaintBrushIcon className="size-5" weight="fill" />
                            <span className="text-sm">Skins</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">My Skins</h1>
                        <p className="text-sm text-muted-foreground">
                            Manage your in-game skin and library uploads.
                        </p>
                    </div>
                    <div className="flex items-center gap-3">
                        <span className="text-sm text-muted-foreground">
                            Slots: <span className="font-semibold text-foreground">{slots.used}</span> / {slots.max}
                        </span>
                        <Button variant="brand" size="sm" asChild>
                            <Link href={create.url()}>
                                <PlusIcon data-icon="inline-start" weight="bold" />
                                Create
                            </Link>
                        </Button>
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div className="space-y-4">
                        <section className="border border-border bg-card p-4">
                            <h2 className="mb-3 text-lg font-semibold tracking-tight">Current in-game skin</h2>
                            {currentSkin.exists && currentSkin.image_url ? (
                                <>
                                    <SkinImage
                                        src={currentSkin.image_url}
                                        className="mx-auto my-4 h-32 w-24"
                                        alt={auth.user?.username ?? 'Current skin'}
                                        width={96}
                                        height={128}
                                    />
                                    <div className="mt-2 flex flex-wrap gap-2">
                                        <Button type="button" size="sm" onClick={() => router.post(duplicate.url())}>
                                            <FloppyDiskIcon data-icon="inline-start" weight="bold" />
                                            Save to My skins
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
                                            <TrashIcon data-icon="inline-start" weight="bold" />
                                            Delete
                                        </Button>
                                    </div>
                                </>
                            ) : (
                                <>
                                    <p className="text-sm text-muted-foreground">
                                        No skins have been added to your account yet.
                                    </p>
                                    {! canImport ? (
                                        <div className="mt-4 border border-border bg-muted/20 px-4 py-3 text-sm text-muted-foreground">
                                            Your slots are full. You cannot import from the old site unless you delete one of the slots.
                                        </div>
                                    ) : (
                                        <Button
                                            type="button"
                                            className="mt-3 w-full"
                                            variant="secondary"
                                            onClick={() => router.post(importUrl)}
                                        >
                                            <DownloadSimpleIcon data-icon="inline-start" weight="bold" />
                                            Check for skin to import from the old site
                                        </Button>
                                    )}
                                </>
                            )}
                        </section>

                        {deleteActivity.length > 0 && (
                            <section className="border border-border bg-card p-4">
                                <h2 className="mb-3 text-lg font-semibold tracking-tight">Admin deletion activity</h2>
                                <div className="space-y-1 text-sm text-muted-foreground">
                                    {deleteActivity.map((log, index) => (
                                        <p key={`${log.created_at}-${index}`} className="m-0">
                                            <span className="text-foreground">{log.created_at}:</span> {log.reason}
                                        </p>
                                    ))}
                                </div>
                            </section>
                        )}

                        <section className="border border-border bg-card p-4">
                            <h2 className="mb-3 text-lg font-semibold tracking-tight">Skin information</h2>
                            <div className="space-y-3 text-sm text-muted-foreground">
                                <p>
                                    Want to make your own skin?{' '}
                                    <a href={templateUrl} className="text-primary hover:underline">
                                        Download this template
                                    </a>{' '}
                                    to get started.
                                </p>
                                <div>
                                    <h3 className="font-medium text-foreground">File validation</h3>
                                    <ul className="mt-1 list-disc space-y-1 pl-5">
                                        <li>Less than 2MB</li>
                                        <li>Must be a PNG file</li>
                                        <li>
                                            Exact dimensions of {width}×{height}
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 className="font-medium text-foreground">Rules</h3>
                                    <ul className="mt-1 list-disc space-y-1 pl-5">
                                        <li>
                                            Every part (for a {width}×{height} sprite, every 32×32 portion) of the skin
                                            has to contain at least one pixel that is not transparent.
                                        </li>
                                        <li>You have to own the rights to use the image you upload.</li>
                                        <li>The image must not contain any sexual or harassing content.</li>
                                    </ul>
                                </div>
                                <p className="text-xs">
                                    If all of the above rules apply to your skin and you upload it, you transfer all
                                    rights to the P3D Team. We can alter and delete your skin as long as it stays on our
                                    servers.
                                </p>
                            </div>
                        </section>
                    </div>

                    <div className="lg:col-span-2">
                        {skins.length === 0 ? (
                            <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                                <PaintBrushIcon className="size-10 text-muted-foreground" weight="fill" />
                                <div className="text-lg font-medium">No skins yet</div>
                                <p className="max-w-md text-sm text-muted-foreground">
                                    Upload a skin to fill one of your {slots.max} slots.
                                </p>
                                <Button variant="brand" size="sm" asChild>
                                    <Link href={create.url()}>
                                        <PlusIcon data-icon="inline-start" weight="bold" />
                                        Create skin
                                    </Link>
                                </Button>
                            </div>
                        ) : (
                            <div className="grid auto-rows-max grid-cols-1 gap-3 sm:grid-cols-2">
                                {skins.map((skin) => (
                                    <SkinCard key={skin.uuid} skin={skin} authenticated />
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}
