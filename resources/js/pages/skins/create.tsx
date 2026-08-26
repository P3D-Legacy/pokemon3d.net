import { Form, Head, Link, usePage } from '@inertiajs/react';
import { PaintBrushIcon, UploadSimpleIcon } from '@phosphor-icons/react';
import { useState } from 'react';

import { store } from '@/actions/App/Http/Controllers/Skin/SkinController';
import { index as skinHome } from '@/actions/App/Http/Controllers/Skin/SkinHomeController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslations } from '@/hooks/use-translations';
import type { SharedPageProps } from '@/types';

type Props = {
    slots: {
        used: number;
        max: number;
    };
    width: number;
    height: number;
};

export default function SkinsCreate({ slots, width, height }: Props) {
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);
    const { flash } = usePage<SharedPageProps>().props;
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Create Skin')} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-2">
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <PaintBrushIcon className="size-5" weight="fill" />
                        <span className="text-sm">{t('Skins')}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{t('Create Skin')}</h1>
                    <p className="text-sm text-muted-foreground">
                        {t('Upload a :width×:height PNG. Slots remaining: :count.', {
                            width,
                            height,
                            count: Math.max(slots.max - slots.used, 0),
                        })}
                    </p>
                </div>

                {(flash.error || flash.banner) && (
                    <div
                        role="alert"
                        className="mb-4 border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive"
                    >
                        {flash.error || flash.banner}
                    </div>
                )}

                <div className="border border-border bg-card p-6">
                    <Form {...store.form()} encType="multipart/form-data" className="space-y-5">
                        {({ processing, errors }) => (
                            <>
                                <div>
                                    <Label htmlFor="name">{t('Name')}</Label>
                                    <Input id="name" name="name" type="text" className="mt-1" autoComplete="name" required />
                                    <InputError message={errors.name} className="mt-2" />
                                </div>
                                <div>
                                    <Label htmlFor="image">{t('Select image file')}</Label>
                                    <Input
                                        id="image"
                                        name="image"
                                        type="file"
                                        accept="image/png"
                                        className="mt-1"
                                        required
                                        onChange={(event) => {
                                            const file = event.target.files?.[0];
                                            if (! file) {
                                                setPreviewUrl(null);

                                                return;
                                            }

                                            setPreviewUrl(URL.createObjectURL(file));
                                        }}
                                    />
                                    <InputError message={errors.image} className="mt-2" />
                                    {previewUrl && (
                                        <img
                                            src={previewUrl}
                                            alt={t('Skin preview')}
                                            className="mt-4 h-32 w-24 object-contain"
                                            width={96}
                                            height={128}
                                        />
                                    )}
                                </div>
                                <div className="flex items-start gap-2">
                                    <Checkbox id="public" name="public" value="1" className="mt-0.5" />
                                    <label htmlFor="public" className="text-sm text-muted-foreground">
                                        <span className="font-medium text-foreground">{t('Public')}</span>{' '}
                                        {t('Other users will be able to see this skin')}
                                    </label>
                                </div>
                                <InputError message={errors.public} />
                                <div className="flex items-start gap-2">
                                    <Checkbox id="rules" name="rules" value="1" required className="mt-0.5" />
                                    <label htmlFor="rules" className="text-sm text-muted-foreground">
                                        <span className="font-medium text-foreground">
                                            {t('I accept and understand the rules')}
                                        </span>{' '}
                                        {t('for uploading a custom skin. Read the rules on the')}{' '}
                                        <Link href={skinHome.url()} className="text-primary hover:underline">
                                            {t('skin home page')}
                                        </Link>
                                        .
                                    </label>
                                </div>
                                <InputError message={errors.rules} />
                                <Button type="submit" variant="default" disabled={processing}>
                                    <UploadSimpleIcon data-icon="inline-start" weight="bold" />
                                    {t('Upload')}
                                </Button>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </>
    );
}
