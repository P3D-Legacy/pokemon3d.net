import { Form, Head } from '@inertiajs/react';
import { PaintBrushIcon, TrashIcon } from '@phosphor-icons/react';
import { useState } from 'react';

import { destroyAsAdmin } from '@/actions/App/Http/Controllers/Skin/PlayerSkinController';
import SkinImage from '@/components/skin-image';
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

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-2">
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <PaintBrushIcon className="size-5" weight="fill" />
                        <span className="text-sm">Admin</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">Player skins</h1>
                    <p className="text-sm text-muted-foreground">
                        Applied in-game skins stored as Game Jolt ID filenames.
                    </p>
                </div>

                {playerSkins.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <PaintBrushIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">None found</div>
                    </div>
                ) : (
                    <div className="grid auto-rows-max grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        {playerSkins.map((playerSkin) => (
                            <div
                                key={playerSkin.filename}
                                className="flex max-w-md gap-4 border border-border bg-card p-4"
                            >
                                <div className="flex shrink-0 items-start justify-center">
                                    <SkinImage
                                        className="h-32 w-24"
                                        src={playerSkin.image_url}
                                        alt={playerSkin.filename}
                                        width={96}
                                        height={128}
                                    />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <h2 className="text-lg font-semibold tracking-tight">{playerSkin.filename}</h2>
                                    <p className="mt-2 space-y-1 text-xs text-muted-foreground">
                                        Owned by: {playerSkin.owner_label}
                                        <br />
                                        File size: {playerSkin.file_size}
                                    </p>
                                    <Form {...destroyAsAdmin.form(playerSkin.gjid)} className="mt-3 w-full">
                                        {({ processing }) => (
                                            <>
                                                <p className="mb-2 text-xs text-muted-foreground">
                                                    Users will be able to see the reason for the deletion.
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
                                                <Button
                                                    type="submit"
                                                    variant="destructive"
                                                    size="sm"
                                                    className="mt-2"
                                                    disabled={processing}
                                                >
                                                    <TrashIcon data-icon="inline-start" weight="bold" />
                                                    Delete
                                                </Button>
                                            </>
                                        )}
                                    </Form>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
