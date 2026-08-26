import { usePage } from '@inertiajs/react';
import { useCallback } from 'react';

import type { SharedPageProps } from '@/types';

type Replacements = Record<string, string | number>;

export function useTranslations() {
    const { translations } = usePage<SharedPageProps>().props;

    const t = useCallback(
        (key: string, replace: Replacements = {}): string => {
            let value = translations[key] ?? key;

            for (const [name, replacement] of Object.entries(replace)) {
                value = value.replaceAll(`:${name}`, String(replacement));
            }

            return value;
        },
        [translations],
    );

    return { t };
}
