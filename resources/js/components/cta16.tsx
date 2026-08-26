import { DownloadSimpleIcon } from '@phosphor-icons/react';

import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

type CtaButton = {
    text: string;
    url: string;
};

type DownloadMeta = {
    released: string;
    releasedLabel: string;
    requirementsLabel: string;
};

interface Cta16Props {
    heading: string;
    description: string;
    image?: {
        src: string;
        alt: string;
    };
    button: CtaButton;
    downloadMeta: DownloadMeta;
    className?: string;
}

const Cta16 = ({
    heading,
    description,
    image = {
        src: '/img/bg.jpg',
        alt: 'Pokémon 3D',
    },
    button,
    downloadMeta,
    className,
}: Cta16Props) => {
    return (
        <section className={cn('relative flex h-[300px] w-full items-center justify-center overflow-hidden md:h-[500px]', className)}>
            <div
                role="img"
                aria-label={image.alt}
                className="absolute inset-0 size-full bg-repeat"
                style={{ backgroundImage: `url(${image.src})` }}
            />
            <div className="absolute inset-0 bg-foreground/40 dark:bg-secondary/60" aria-hidden />
            <div className="relative z-10 mx-auto flex w-full max-w-5xl flex-col gap-8 p-4 text-center text-white">
                <h2 className="mx-auto max-w-3xl text-2xl font-semibold tracking-tight text-balance drop-shadow-md md:text-5xl">
                    {heading}
                </h2>
                <p className="text-base font-medium text-white/90 drop-shadow md:text-lg">{description}</p>
                <div className="flex flex-col items-center justify-center gap-2">
                    <Button size="2xl" variant="secondary" asChild>
                        <a href={button.url}>
                            <DownloadSimpleIcon data-icon="inline-start" />
                            {button.text}
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
        </section>
    );
};

export { Cta16 };
