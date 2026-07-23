import { useEffect, useState } from 'react';

import {
    applyTheme,
    getStoredTheme,
    persistTheme,
    resolveTheme,
    type Theme,
} from '@/lib/theme';

const THEME_CHANGE_EVENT = 'pokemon3d:theme-change';

function broadcastTheme(theme: Theme): void {
    if (typeof window === 'undefined') {
        return;
    }

    window.dispatchEvent(new CustomEvent<Theme>(THEME_CHANGE_EVENT, { detail: theme }));
}

export function useTheme() {
    const [theme, setThemeState] = useState<Theme>(() => resolveTheme());

    useEffect(() => {
        const resolved = resolveTheme(getStoredTheme());
        setThemeState(resolved);
        applyTheme(resolved);

        const onThemeChange = (event: Event) => {
            const next = (event as CustomEvent<Theme>).detail;

            if (next === 'light' || next === 'dark') {
                setThemeState(next);
            }
        };

        window.addEventListener(THEME_CHANGE_EVENT, onThemeChange);

        return () => {
            window.removeEventListener(THEME_CHANGE_EVENT, onThemeChange);
        };
    }, []);

    const setTheme = (next: Theme) => {
        setThemeState(next);
        applyTheme(next);
        persistTheme(next);
        broadcastTheme(next);
    };

    const toggleTheme = () => {
        setTheme(theme === 'dark' ? 'light' : 'dark');
    };

    return { theme, setTheme, toggleTheme } as const;
}
