import { usePage } from '@inertiajs/react';
import { CheckIcon } from '@phosphor-icons/react';

import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { cn } from '@/lib/utils';
import type { SharedPageProps } from '@/types';

type LanguageNavProps = {
    className?: string;
    /** When true, use overlay chrome styling (white icons on dark hero). */
    overlay?: boolean;
};

function flagSrc(countryCode: string): string {
    return `/img/vendor/language/flags/${countryCode}.png`;
}

export function LanguageNav({ className, overlay = false }: LanguageNavProps) {
    const { languages } = usePage<SharedPageProps>().props;
    const contributeIsExternal =
        languages.contribute_url.startsWith('http://') || languages.contribute_url.startsWith('https://');

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    type="button"
                    variant="ghost"
                    size="icon"
                    aria-label={languages.current_name}
                    className={cn(
                        overlay && 'text-white hover:bg-white/10 hover:text-white',
                        className,
                    )}
                >
                    <img
                        src={flagSrc(languages.current_flag)}
                        alt={languages.current_name}
                        className="h-auto w-5"
                        width={20}
                        height={15}
                    />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56">
                {languages.options.map((language) => {
                    const isCurrent = language.code === languages.current;

                    return (
                        <DropdownMenuItem key={language.code} asChild>
                            <a
                                href={language.url}
                                className="flex items-center gap-2"
                                aria-current={isCurrent ? 'true' : undefined}
                            >
                                <img
                                    src={flagSrc(language.flag)}
                                    alt=""
                                    className="h-auto w-5 shrink-0"
                                    width={20}
                                    height={15}
                                />
                                <span className="flex-1">{language.name}</span>
                                {isCurrent ? <CheckIcon className="size-4 shrink-0" /> : null}
                            </a>
                        </DropdownMenuItem>
                    );
                })}
                <DropdownMenuSeparator />
                <DropdownMenuItem asChild>
                    <a
                        href={languages.contribute_url}
                        className="text-xs text-muted-foreground"
                        {...(contributeIsExternal
                            ? { target: '_blank', rel: 'noreferrer' }
                            : {})}
                    >
                        {languages.contribute_label}
                    </a>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
