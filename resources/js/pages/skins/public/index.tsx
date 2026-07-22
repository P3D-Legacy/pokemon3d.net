import { Head, Link, usePage } from '@inertiajs/react';
import { ClockIcon, FireIcon, PaintBrushIcon, PersonSimpleWalkIcon } from '@phosphor-icons/react';

import SkinCard from '@/components/skin-card';
import { Button } from '@/components/ui/button';
import { useSkinAnimationPreference } from '@/hooks/use-skin-animation-preference';
import { cn, paginationLabel } from '@/lib/utils';
import { skinsNewest, skinsPopular } from '@/routes';
import type { Paginated, SharedPageProps, SkinCardData } from '@/types';

type Props = {
    skins: Paginated<SkinCardData>;
    sort: 'newest' | 'popular';
};

export default function SkinsPublicIndex({ skins, sort }: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const title = sort === 'popular' ? 'Most Popular Skins' : 'Newest Skins';
    const [animate, setAnimate] = useSkinAnimationPreference();

    return (
        <>
            <Head title={title} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-2">
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <PaintBrushIcon className="size-5" weight="fill" />
                        <span className="text-sm">Skins</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{title}</h1>
                    <p className="max-w-2xl text-sm text-muted-foreground">
                        Browse public player skins shared by the community.
                    </p>
                </div>

                <div className="mb-6 flex flex-wrap items-center gap-2">
                    <Button variant={sort === 'newest' ? 'default' : 'outline'} size="sm" asChild>
                        <Link href={skinsNewest.url()}>
                            <ClockIcon data-icon="inline-start" weight="bold" />
                            Newest
                        </Link>
                    </Button>
                    <Button variant={sort === 'popular' ? 'default' : 'outline'} size="sm" asChild>
                        <Link href={skinsPopular.url()}>
                            <FireIcon data-icon="inline-start" weight="fill" />
                            Most Popular
                        </Link>
                    </Button>
                    <Button
                        type="button"
                        className="ml-auto"
                        variant={animate ? 'default' : 'outline'}
                        size="sm"
                        aria-pressed={animate}
                        onClick={() => setAnimate((current) => ! current)}
                    >
                        <PersonSimpleWalkIcon data-icon="inline-start" weight="bold" />
                        {animate ? 'Animation on' : 'Animation off'}
                    </Button>
                </div>

                {skins.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <PaintBrushIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">None found</div>
                        <p className="max-w-md text-sm text-muted-foreground">
                            There are no public skins to show yet.
                        </p>
                    </div>
                ) : (
                    <div className="grid auto-rows-max grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        {skins.data.map((skin) => (
                            <SkinCard
                                key={skin.uuid}
                                skin={skin}
                                authenticated={Boolean(auth.user)}
                                animate={animate}
                            />
                        ))}
                    </div>
                )}

                {skins.links.length > 3 ? (
                    <div className="mt-8 flex flex-wrap items-center justify-center gap-2">
                        {skins.links.map((link, index) =>
                            link.url ? (
                                <Button
                                    key={`${link.label}-${index}`}
                                    variant={link.active ? 'default' : 'outline'}
                                    size="sm"
                                    asChild
                                >
                                    <Link
                                        href={link.url}
                                        className={cn(! link.active && 'text-muted-foreground')}
                                    >
                                        {paginationLabel(link.label)}
                                    </Link>
                                </Button>
                            ) : (
                                <Button key={`${link.label}-${index}`} variant="outline" size="sm" disabled>
                                    {paginationLabel(link.label)}
                                </Button>
                            ),
                        )}
                    </div>
                ) : null}
            </div>
        </>
    );
}
