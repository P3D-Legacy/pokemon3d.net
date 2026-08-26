import { useEffect, useState } from 'react';

import { cn } from '@/lib/utils';

export const SKIN_FALLBACK_IMAGE = '/img/noskin.png';

type Props = {
    src: string | null | undefined;
    alt: string;
    className?: string;
    width?: number;
    height?: number;
};

export default function SkinImage({ src, alt, className, width = 96, height = 128 }: Props) {
    const [currentSrc, setCurrentSrc] = useState(src || SKIN_FALLBACK_IMAGE);

    useEffect(() => {
        setCurrentSrc(src || SKIN_FALLBACK_IMAGE);
    }, [src]);

    return (
        <img
            src={currentSrc}
            alt={alt}
            className={cn('object-contain', className)}
            width={width}
            height={height}
            onError={() => {
                if (currentSrc !== SKIN_FALLBACK_IMAGE) {
                    setCurrentSrc(SKIN_FALLBACK_IMAGE);
                }
            }}
        />
    );
}
