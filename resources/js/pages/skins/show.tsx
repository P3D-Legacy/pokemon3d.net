import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowLeftIcon, PaintBrushIcon, PersonSimpleWalkIcon } from '@phosphor-icons/react';
import { useState } from 'react';

import SkinAnimator from '@/components/skin-animator';
import SkinCard from '@/components/skin-card';
import SkinImage from '@/components/skin-image';
import { Button } from '@/components/ui/button';
import { useSkinAnimationPreference } from '@/hooks/use-skin-animation-preference';
import { useTranslations } from '@/hooks/use-translations';
import { skinsNewest } from '@/routes';
import type { SharedPageProps, SkinCardData } from '@/types';

type Props = {
    skin: SkinCardData;
};

export default function SkinsShow({ skin }: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const { t } = useTranslations();
    const [animate, setAnimate] = useSkinAnimationPreference();
    const [animationAvailable, setAnimationAvailable] = useState(true);
    const showAnimator = animate && animationAvailable;

    return (
        <>
            <Head title={t('Skin: :name', { name: skin.name })} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-4">
                    <div className="flex flex-wrap items-center gap-2">
                        <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                            <Link href={skinsNewest.url()}>
                                <ArrowLeftIcon className="size-4" />
                                {t('Back to public skins')}
                            </Link>
                        </Button>
                        <Button
                            type="button"
                            className="ml-auto"
                            variant={animate ? 'default' : 'outline'}
                            size="sm"
                            aria-pressed={animate}
                            onClick={() => {
                                setAnimate((current) => {
                                    const next = ! current;

                                    if (next) {
                                        setAnimationAvailable(true);
                                    }

                                    return next;
                                });
                            }}
                        >
                            <PersonSimpleWalkIcon data-icon="inline-start" weight="bold" />
                            {animate ? t('Animation on') : t('Animation off')}
                        </Button>
                    </div>
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <PaintBrushIcon className="size-5" weight="fill" />
                            <span className="text-sm">{t('Public skin')}</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{skin.name}</h1>
                        <p className="text-sm text-muted-foreground">
                            {t('Uploaded')} {skin.uploaded_at}
                            {skin.publisher ? ` ${t('by :username', { username: skin.publisher.username })}` : ''}
                        </p>
                    </div>
                </div>

                <div className="flex flex-col gap-6 lg:flex-row lg:items-start">
                    <div className="flex w-60 shrink-0 flex-col items-center gap-3 border border-border bg-card p-6">
                        <div className="flex h-64 w-48 items-center justify-center">
                            {showAnimator ? (
                                <SkinAnimator
                                    src={skin.image_url}
                                    alt={skin.name}
                                    scale={6}
                                    onUnavailable={() => setAnimationAvailable(false)}
                                />
                            ) : (
                                <SkinImage
                                    className="h-64 w-48"
                                    src={skin.image_url}
                                    alt={skin.name}
                                    width={192}
                                    height={256}
                                />
                            )}
                        </div>
                        <p className="w-48 text-center text-xs text-muted-foreground">
                            {showAnimator
                                ? t('Overworld walk cycle from the 96×128 sheet (32×32 frames).')
                                : t('Full 96×128 skin sheet.')}
                        </p>
                    </div>
                    <SkinCard
                        skin={skin}
                        mode="detail"
                        authenticated={Boolean(auth.user)}
                        className="max-w-none flex-1"
                        hideImage
                    />
                </div>
            </div>
        </>
    );
}
