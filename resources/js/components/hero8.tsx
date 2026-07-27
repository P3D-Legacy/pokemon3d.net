import { DownloadSimpleIcon } from '@phosphor-icons/react';
import type { ReactNode } from 'react';

import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { download } from '@/routes';

type DownloadMeta = {
    version: string;
    released: string;
    downloadLabel: string;
    releasedLabel: string;
    requirementsLabel: string;
};

interface Hero8Props {
    heading: string;
    description: string;
    downloadMeta: DownloadMeta;
    videoSrc?: string;
    backgroundImage?: string;
    children?: ReactNode;
    className?: string;
}

const Hero8 = ({
    heading,
    description,
    downloadMeta,
    videoSrc = 'https://files.pokemon3d.net/video/trailer.mp4',
    backgroundImage = '/img/bg.jpg',
    children,
    className,
}: Hero8Props) => {
    return (
        <section className={cn('relative overflow-hidden pb-0 text-white', className)}>
            <div
                role="img"
                aria-label="Pokémon 3D"
                className="absolute inset-0 size-full bg-repeat"
                style={{ backgroundImage: `url(${backgroundImage})` }}
            />
            <div className="absolute inset-0 bg-foreground/50 dark:bg-secondary/60" aria-hidden />

            <div className="relative z-10">
                {children ? <div className="relative z-20">{children}</div> : null}

                <div className="relative z-0 container mx-auto max-w-7xl px-4 pt-8 md:pt-12">
                    <div className="mx-auto flex max-w-5xl flex-col items-center">
                        <div className="flex flex-col items-center gap-6 text-center">
                            <h1 className="mx-auto max-w-xl text-4xl font-semibold tracking-tight text-pretty drop-shadow-md md:text-5xl lg:max-w-3xl lg:text-6xl">
                                {heading}
                            </h1>
                            <p className="mx-auto max-w-3xl text-lg text-balance text-white/95 drop-shadow md:text-xl">
                                {description}
                            </p>
                            <div className="flex w-full flex-col items-center gap-2">
                                <Button size="2xl" asChild className="w-full sm:w-auto">
                                    <a href={download.url()}>
                                        <DownloadSimpleIcon data-icon="inline-start" />
                                        {downloadMeta.downloadLabel} {downloadMeta.version}
                                        <sup>&dagger;</sup>
                                    </a>
                                </Button>
                                <p className="text-xs text-white/90 drop-shadow">
                                    <span>
                                        {downloadMeta.releasedLabel} {downloadMeta.released}
                                    </span>
                                    <span className="px-2">&mdash;</span>
                                    <a
                                        href="https://wiki.pokemon3d.net/index.php/Pok%C3%A9mon_3D#Requirements"
                                        className="hover:text-white"
                                    >
                                        <sup>&dagger;</sup> {downloadMeta.requirementsLabel}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="mx-auto mt-12 overflow-hidden border-x border-t border-border bg-background shadow-[0_10px_36px_-10px_rgb(0_0_0/0.22)] md:mt-16">
                        <video muted controls className="aspect-video w-full object-cover object-center">
                            <source src={videoSrc} type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>
        </section>
    );
};

export { Hero8 };
