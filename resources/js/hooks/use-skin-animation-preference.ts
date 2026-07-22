import { useEffect, useState } from 'react';

export const SKIN_ANIMATE_STORAGE_KEY = 'skins.public.animate';

export function useSkinAnimationPreference(defaultEnabled = true) {
    const [animate, setAnimate] = useState(defaultEnabled);
    const [hydrated, setHydrated] = useState(false);

    useEffect(() => {
        const stored = window.localStorage.getItem(SKIN_ANIMATE_STORAGE_KEY);

        if (stored === '1' || stored === '0') {
            setAnimate(stored === '1');
        } else if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            setAnimate(false);
        }

        setHydrated(true);
    }, []);

    useEffect(() => {
        if (! hydrated) {
            return;
        }

        window.localStorage.setItem(SKIN_ANIMATE_STORAGE_KEY, animate ? '1' : '0');
    }, [animate, hydrated]);

    return [animate, setAnimate] as const;
}
