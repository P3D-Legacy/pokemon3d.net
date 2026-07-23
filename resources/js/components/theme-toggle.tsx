import { MoonIcon, SunIcon } from '@phosphor-icons/react';

import { Button } from '@/components/ui/button';
import { useTheme } from '@/hooks/use-theme';
import { cn } from '@/lib/utils';

type ThemeToggleProps = {
    className?: string;
    /** When true, use overlay chrome styling (white icons on dark hero). */
    overlay?: boolean;
};

export function ThemeToggle({ className, overlay = false }: ThemeToggleProps) {
    const { theme, toggleTheme } = useTheme();
    const isDark = theme === 'dark';

    return (
        <Button
            type="button"
            variant="ghost"
            size="icon"
            onClick={toggleTheme}
            aria-label={isDark ? 'Switch to light theme' : 'Switch to dark theme'}
            className={cn(
                overlay && 'text-white hover:bg-white/10 hover:text-white',
                className,
            )}
        >
            {isDark ? <SunIcon className="size-5" /> : <MoonIcon className="size-5" />}
        </Button>
    );
}
