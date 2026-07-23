export const THEME_STORAGE_KEY = 'theme';

export type Theme = 'light' | 'dark';

export function getStoredTheme(): Theme | null {
    if (typeof window === 'undefined') {
        return null;
    }

    const stored = window.localStorage.getItem(THEME_STORAGE_KEY);

    if (stored === 'light' || stored === 'dark') {
        return stored;
    }

    return null;
}

export function getSystemTheme(): Theme {
    if (typeof window === 'undefined') {
        return 'light';
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

export function resolveTheme(stored: Theme | null = getStoredTheme()): Theme {
    return stored ?? getSystemTheme();
}

export function applyTheme(theme: Theme): void {
    if (typeof document === 'undefined') {
        return;
    }

    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.documentElement.style.colorScheme = theme;
}

export function persistTheme(theme: Theme): void {
    if (typeof window === 'undefined') {
        return;
    }

    window.localStorage.setItem(THEME_STORAGE_KEY, theme);
}
