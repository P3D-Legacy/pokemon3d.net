import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowLeftIcon, PaintBrushIcon } from '@phosphor-icons/react';

import SkinAnimator from '@/components/skin-animator';
import SkinCard from '@/components/skin-card';
import { Button } from '@/components/ui/button';
import { skinsNewest } from '@/routes';
import type { SharedPageProps, SkinCardData } from '@/types';

type Props = {
    skin: SkinCardData;
};

export default function SkinsShow({ skin }: Props) {
    const { auth } = usePage<SharedPageProps>().props;

    return (
        <>
            <Head title={`Skin: ${skin.name}`} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-4">
                    <Button variant="default" size="sm" asChild>
                        <Link href={skinsNewest.url()}>
                            <ArrowLeftIcon data-icon="inline-start" weight="bold" />
                            Back to public skins
                        </Link>
                    </Button>
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <PaintBrushIcon className="size-5" weight="fill" />
                            <span className="text-sm">Public skin</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{skin.name}</h1>
                        <p className="text-sm text-muted-foreground">
                            Uploaded {skin.uploaded_at}
                            {skin.publisher ? ` by ${skin.publisher.username}` : ''}
                        </p>
                    </div>
                </div>

                <div className="flex flex-col gap-6 lg:flex-row lg:items-start">
                    <div className="flex flex-col items-center gap-3 border border-border bg-card p-6">
                        <SkinAnimator src={skin.image_url} alt={skin.name} scale={6} />
                        <p className="text-center text-xs text-muted-foreground">
                            Overworld walk cycle from the 96×128 sheet (32×32 frames).
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
