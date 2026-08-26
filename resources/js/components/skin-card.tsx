import { Form, Link, router } from '@inertiajs/react';
import {
    EyeIcon,
    FloppyDiskIcon,
    HeartIcon,
    PencilSimpleIcon,
    TrashIcon,
} from '@phosphor-icons/react';
import { useEffect, useState } from 'react';

import {
    apply,
    destroy,
    edit,
    like,
    show,
} from '@/actions/App/Http/Controllers/Skin/SkinController';
import { destroy as destroyUploaded } from '@/actions/App/Http/Controllers/Skin/UploadedSkinController';
import SkinAnimator from '@/components/skin-animator';
import SkinImage from '@/components/skin-image';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import type { SkinCardData } from '@/types';

type Props = {
    skin: SkinCardData;
    mode?: 'default' | 'admin' | 'detail';
    authenticated?: boolean;
    className?: string;
    hideImage?: boolean;
    /** Show the overworld walk cycle instead of the full sheet image. */
    animate?: boolean;
};

export default function SkinCard({
    skin,
    mode = 'default',
    authenticated = false,
    className,
    hideImage = false,
    animate = false,
}: Props) {
    const { t } = useTranslations();
    const [reason, setReason] = useState('');
    const [processing, setProcessing] = useState(false);
    const [animationAvailable, setAnimationAvailable] = useState(animate);

    useEffect(() => {
        if (animate) {
            setAnimationAvailable(true);
        }
    }, [animate, skin.image_url]);

    const runAction = (action: () => void) => {
        if (processing) {
            return;
        }

        setProcessing(true);
        action();
    };

    const showAnimator = animate && animationAvailable;

    return (
        <div
            className={cn(
                'flex max-w-md gap-4 border border-border bg-card p-4 transition-colors',
                mode === 'detail' && 'max-w-2xl',
                className,
            )}
        >
            {! hideImage && (
                <div
                    className={cn(
                        'flex shrink-0 items-center justify-center',
                        mode === 'detail' ? 'h-64 w-48' : 'h-32 w-24',
                    )}
                >
                    {showAnimator ? (
                        <SkinAnimator
                            src={skin.image_url}
                            alt={skin.name}
                            scale={mode === 'detail' ? 6 : 3}
                            onUnavailable={() => setAnimationAvailable(false)}
                        />
                    ) : (
                        <SkinImage
                            className={mode === 'detail' ? 'h-64 w-48' : 'h-32 w-24'}
                            src={skin.image_url}
                            alt={skin.name}
                            width={mode === 'detail' ? 192 : 96}
                            height={mode === 'detail' ? 256 : 128}
                        />
                    )}
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
                    {skin.is_owner && (
                        <p>{t('Public: :value', { value: skin.public ? t('Yes') : t('No') })}</p>
                    )}
                    {skin.publisher ? (
                        <p>
                            {t('Published by:')}{' '}
                            <Link className="text-primary hover:underline" href={skin.publisher.url}>
                                {skin.publisher.username}
                            </Link>
                        </p>
                    ) : (
                        <p>{t('Game Jolt ID: :id', { id: skin.owner_id })}</p>
                    )}
                    <p>{t('Uploaded: :date', { date: skin.uploaded_at })}</p>
                    <p>{t('File size: :size', { size: skin.file_size })}</p>
                    <p className="text-sm text-foreground">{t(':count likes', { count: skin.likes_count })}</p>
                </div>

                {mode === 'admin' ? (
                    <Form {...destroyUploaded.form(skin.uuid)} className="mt-3 w-full" onSuccess={() => setReason('')}>
                        {({ processing: formProcessing }) => (
                            <>
                                <p className="mb-2 text-xs text-muted-foreground">
                                    {t('Users will be able to see the reason for the deletion.')}
                                </p>
                                <Input
                                    name="reason"
                                    value={reason}
                                    onChange={(event) => setReason(event.target.value)}
                                    placeholder={t('Add a legit reason here')}
                                    required
                                />
                                <Button type="submit" variant="destructive" size="sm" className="mt-2" disabled={formProcessing}>
                                    <TrashIcon data-icon="inline-start" weight="bold" />
                                    {t('Delete')}
                                </Button>
                            </>
                        )}
                    </Form>
                ) : (
                    <div className="mt-3 flex flex-wrap gap-2">
                        {mode !== 'detail' && skin.public && skin.show_url && (
                            <Button variant="outline" size="sm" asChild>
                                <Link href={show.url(skin.uuid)}>
                                    <EyeIcon data-icon="inline-start" weight="bold" />
                                    {t('Show')}
                                </Link>
                            </Button>
                        )}
                        {authenticated && ! skin.is_owner && (
                            <Button
                                type="button"
                                size="sm"
                                variant={skin.liked ? 'like' : 'outline'}
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
                                {skin.liked ? t('Liked') : t('Like')}
                            </Button>
                        )}
                        {authenticated && skin.is_owner && (
                            <>
                                <Button variant="outline" size="sm" asChild>
                                    <Link href={edit.url(skin.uuid)}>
                                        <PencilSimpleIcon data-icon="inline-start" weight="bold" />
                                        {t('Edit')}
                                    </Link>
                                </Button>
                                <Button
                                    type="button"
                                    size="sm"
                                    variant="destructive"
                                    disabled={processing}
                                    onClick={() => {
                                        if (confirm(t('Delete this skin?'))) {
                                            runAction(() =>
                                                router.delete(destroy.url(skin.uuid), {
                                                    onFinish: () => setProcessing(false),
                                                }),
                                            );
                                        }
                                    }}
                                >
                                    <TrashIcon data-icon="inline-start" weight="bold" />
                                    {t('Delete')}
                                </Button>
                            </>
                        )}
                        {authenticated && (
                            <Button
                                type="button"
                                size="sm"
                                variant="secondary"
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
                                {t('Apply')}
                            </Button>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
}
