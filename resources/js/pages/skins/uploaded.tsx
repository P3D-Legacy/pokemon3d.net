import { Head, Link } from '@inertiajs/react';
import { PaintBrushIcon } from '@phosphor-icons/react';

import SkinCard from '@/components/skin-card';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import { cn, paginationLabel } from '@/lib/utils';
import type { Paginated, SkinCardData } from '@/types';

type Props = {
    skins: Paginated<SkinCardData>;
};

export default function SkinsUploaded({ skins }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Uploaded Skins')} />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-2">
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <PaintBrushIcon className="size-5" weight="fill" />
                        <span className="text-sm">{t('Admin')}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{t('Uploaded Skins')}</h1>
                    <p className="text-sm text-muted-foreground">
                        {t('Review and remove library skins when needed.')}
                    </p>
                </div>

                {skins.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                        <PaintBrushIcon className="size-10 text-muted-foreground" weight="fill" />
                        <div className="text-lg font-medium">{t('None found.')}</div>
                    </div>
                ) : (
                    <div className="grid auto-rows-max grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        {skins.data.map((skin) => (
                            <SkinCard key={skin.uuid} skin={skin} mode="admin" />
                        ))}
                    </div>
                )}

                {skins.links.length > 3 ? (
                    <div className="mt-8 flex flex-wrap items-center justify-center gap-2">
                        {skins.links.map((link, index) =>
                            link.url ? (
                                <Button
                                    key={`${link.label}-${index}`}
                                    variant={link.active ? 'default' : 'outline'}
                                    size="sm"
                                    asChild
                                >
                                    <Link
                                        href={link.url}
                                        className={cn(! link.active && 'text-muted-foreground')}
                                    >
                                        {paginationLabel(link.label)}
                                    </Link>
                                </Button>
                            ) : (
                                <Button key={`${link.label}-${index}`} variant="outline" size="sm" disabled>
                                    {paginationLabel(link.label)}
                                </Button>
                            ),
                        )}
                    </div>
                ) : null}
            </div>
        </>
    );
}
