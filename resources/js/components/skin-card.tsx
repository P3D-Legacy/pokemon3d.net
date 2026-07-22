import { Form, Link, router } from '@inertiajs/react';
import {
    EyeIcon,
    FloppyDiskIcon,
    HeartIcon,
    PencilSimpleIcon,
    TrashIcon,
} from '@phosphor-icons/react';
import { useState } from 'react';

import {
    apply,
    destroy,
    edit,
    like,
    show,
} from '@/actions/App/Http/Controllers/Skin/SkinController';
import { destroy as destroyUploaded } from '@/actions/App/Http/Controllers/Skin/UploadedSkinController';
import SkinImage from '@/components/skin-image';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import type { SkinCardData } from '@/types';

type Props = {
    skin: SkinCardData;
    mode?: 'default' | 'admin' | 'detail';
    authenticated?: boolean;
    className?: string;
    hideImage?: boolean;
};

export default function SkinCard({
    skin,
    mode = 'default',
    authenticated = false,
    className,
    hideImage = false,
}: Props) {
    const [reason, setReason] = useState('');
    const [processing, setProcessing] = useState(false);

    const runAction = (action: () => void) => {
        if (processing) {
            return;
        }

        setProcessing(true);
        action();
    };

    return (
        <div
            className={cn(
                'flex max-w-md gap-4 border border-border bg-card p-4 transition-colors',
                mode === 'detail' && 'max-w-2xl',
                className,
            )}
        >
            {! hideImage && (
                <div className="flex shrink-0 items-start justify-center">
                    <SkinImage
                        className={mode === 'detail' ? 'h-64 w-48' : 'h-32 w-24'}
                        src={skin.image_url}
                        alt={skin.name}
                        width={mode === 'detail' ? 192 : 96}
                        height={mode === 'detail' ? 256 : 128}
                    />
                </div>
            )}
            <div className="min-w-0 flex-1">
                <h2 className="break-all text-lg font-semibold tracking-tight text-foreground">
                    {skin.public && skin.show_url ? (
                        <Link href={skin.show_url} className="hover:text-primary">
                            {skin.name}
                        </Link>
                    ) : (
                        skin.name
                    )}
                </h2>
                <div className="mt-2 space-y-1 text-xs text-muted-foreground">
                    {skin.is_owner && <p>Public: {skin.public ? 'Yes' : 'No'}</p>}
                    {skin.publisher ? (
                        <p>
                            Published by:{' '}
                            <Link className="text-primary hover:underline" href={skin.publisher.url}>
                                {skin.publisher.username}
                            </Link>
                        </p>
                    ) : (
                        <p>Game Jolt ID: {skin.owner_id}</p>
                    )}
                    <p>Uploaded: {skin.uploaded_at}</p>
                    <p>File size: {skin.file_size}</p>
                    <p className="text-sm text-foreground">{skin.likes_count} likes</p>
                </div>

                {mode === 'admin' ? (
                    <Form {...destroyUploaded.form(skin.uuid)} className="mt-3 w-full" onSuccess={() => setReason('')}>
                        {({ processing: formProcessing }) => (
                            <>
                                <p className="mb-2 text-xs text-muted-foreground">
                                    Users will be able to see the reason for the deletion.
                                </p>
                                <Input
                                    name="reason"
                                    value={reason}
                                    onChange={(event) => setReason(event.target.value)}
                                    placeholder="Add a legit reason here"
                                    required
                                />
                                <Button type="submit" variant="destructive" size="sm" className="mt-2" disabled={formProcessing}>
                                    <TrashIcon data-icon="inline-start" weight="bold" />
                                    Delete
                                </Button>
                            </>
                        )}
                    </Form>
                ) : (
                    <div className="mt-3 flex flex-wrap gap-2">
                        {mode !== 'detail' && skin.public && skin.show_url && (
                            <Button variant="default" size="sm" asChild>
                                <Link href={show.url(skin.uuid)}>
                                    <EyeIcon data-icon="inline-start" weight="bold" />
                                    Show
                                </Link>
                            </Button>
                        )}
                        {authenticated && ! skin.is_owner && (
                            <Button
                                type="button"
                                size="sm"
                                variant="default"
                                disabled={processing}
                                onClick={() =>
                                    runAction(() =>
                                        router.post(like.url(skin.uuid), {}, {
                                            onFinish: () => setProcessing(false),
                                        }),
                                    )
                                }
                            >
                                <HeartIcon data-icon="inline-start" weight="fill" />
                                {skin.liked ? 'Liked' : 'Like'}
                            </Button>
                        )}
                        {authenticated && skin.is_owner && (
                            <>
                                <Button variant="default" size="sm" asChild>
                                    <Link href={edit.url(skin.uuid)}>
                                        <PencilSimpleIcon data-icon="inline-start" weight="bold" />
                                        Edit
                                    </Link>
                                </Button>
                                <Button
                                    type="button"
                                    size="sm"
                                    variant="destructive"
                                    disabled={processing}
                                    onClick={() => {
                                        if (confirm('Delete this skin?')) {
                                            runAction(() =>
                                                router.delete(destroy.url(skin.uuid), {
                                                    onFinish: () => setProcessing(false),
                                                }),
                                            );
                                        }
                                    }}
                                >
                                    <TrashIcon data-icon="inline-start" weight="bold" />
                                    Delete
                                </Button>
                            </>
                        )}
                        {authenticated && (
                            <Button
                                type="button"
                                size="sm"
                                variant="default"
                                disabled={processing}
                                onClick={() =>
                                    runAction(() =>
                                        router.post(apply.url(skin.uuid), {}, {
                                            onFinish: () => setProcessing(false),
                                        }),
                                    )
                                }
                            >
                                <FloppyDiskIcon data-icon="inline-start" weight="bold" />
                                Apply
                            </Button>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
}
