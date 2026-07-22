import { useEffect, useRef, useState } from 'react';

import { SKIN_FALLBACK_IMAGE } from '@/components/skin-image';
import { cn } from '@/lib/utils';

const FRAME_SIZE = 32;
const COLUMNS = 3;
const ROWS = 4;
/** Classic overworld walk: idle → step A → idle → step B */
const WALK_SEQUENCE = [0, 1, 0, 2] as const;
const FRAME_MS = 160;
const DIRECTION_MS = 2_400;

type Props = {
    src: string | null | undefined;
    alt: string;
    /** Display scale for each 32×32 frame (default 6 → 192px). */
    scale?: number;
    className?: string;
    /** Called when the skin image cannot be loaded. */
    onUnavailable?: () => void;
};

function isAnimatableSrc(src: string | null | undefined): src is string {
    return Boolean(src) && src !== SKIN_FALLBACK_IMAGE && ! src.includes('/img/noskin.png');
}

export default function SkinAnimator({ src, alt, scale = 6, className, onUnavailable }: Props) {
    const [currentSrc, setCurrentSrc] = useState(isAnimatableSrc(src) ? src : SKIN_FALLBACK_IMAGE);
    const [imageReady, setImageReady] = useState(false);
    const [frameIndex, setFrameIndex] = useState(0);
    const [direction, setDirection] = useState(2);
    const [reducedMotion, setReducedMotion] = useState(false);
    const imageRef = useRef<HTMLImageElement>(null);
    const onUnavailableRef = useRef(onUnavailable);

    onUnavailableRef.current = onUnavailable;

    const displaySize = FRAME_SIZE * scale;
    const canAnimate = imageReady && ! reducedMotion && isAnimatableSrc(currentSrc);
    const column = canAnimate ? WALK_SEQUENCE[frameIndex % WALK_SEQUENCE.length] : 0;

    useEffect(() => {
        setFrameIndex(0);
        setDirection(2);
        setImageReady(false);

        if (! isAnimatableSrc(src)) {
            setCurrentSrc(SKIN_FALLBACK_IMAGE);
            onUnavailableRef.current?.();

            return;
        }

        setCurrentSrc(src);
    }, [src]);

    useEffect(() => {
        const image = imageRef.current;

        if (! image || ! isAnimatableSrc(currentSrc)) {
            return;
        }

        // Cached images often skip onLoad when the animator remounts.
        if (image.complete && image.naturalWidth > 0) {
            setImageReady(true);
        }
    }, [currentSrc]);

    useEffect(() => {
        const media = window.matchMedia('(prefers-reduced-motion: reduce)');
        const sync = () => setReducedMotion(media.matches);
        sync();
        media.addEventListener('change', sync);

        return () => media.removeEventListener('change', sync);
    }, []);

    useEffect(() => {
        if (! canAnimate) {
            return;
        }

        const frameTimer = window.setInterval(() => {
            setFrameIndex((current) => (current + 1) % WALK_SEQUENCE.length);
        }, FRAME_MS);

        const directionTimer = window.setInterval(() => {
            setDirection((current) => (current + 1) % ROWS);
        }, DIRECTION_MS);

        return () => {
            window.clearInterval(frameTimer);
            window.clearInterval(directionTimer);
        };
    }, [canAnimate]);

    return (
        <div
            className={cn('relative overflow-hidden bg-muted/30', className)}
            style={{ width: displaySize, height: displaySize }}
            aria-label={canAnimate ? `${alt} animated preview` : `${alt} preview`}
        >
            <img
                ref={imageRef}
                src={currentSrc}
                alt={alt}
                width={FRAME_SIZE * COLUMNS * scale}
                height={FRAME_SIZE * ROWS * scale}
                draggable={false}
                className="absolute top-0 left-0 max-w-none select-none"
                style={{
                    width: displaySize * COLUMNS,
                    height: displaySize * ROWS,
                    imageRendering: 'pixelated',
                    transform: `translate(${-column * displaySize}px, ${-direction * displaySize}px)`,
                }}
                onLoad={(event) => {
                    if (isAnimatableSrc(currentSrc) && event.currentTarget.naturalWidth > 0) {
                        setImageReady(true);
                    }
                }}
                onError={() => {
                    setImageReady(false);

                    if (currentSrc !== SKIN_FALLBACK_IMAGE) {
                        setCurrentSrc(SKIN_FALLBACK_IMAGE);
                        onUnavailableRef.current?.();
                    }
                }}
            />
        </div>
    );
}
