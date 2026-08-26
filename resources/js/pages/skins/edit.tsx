import { Form, Head } from '@inertiajs/react';
import { FloppyDiskIcon, PaintBrushIcon } from '@phosphor-icons/react';

import { update } from '@/actions/App/Http/Controllers/Skin/SkinController';
import InputError from '@/components/input-error';
import SkinImage from '@/components/skin-image';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslations } from '@/hooks/use-translations';

type Props = {
    skin: {
        uuid: string;
        name: string;
        public: boolean;
        image_url: string;
    };
};

export default function SkinsEdit({ skin }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Edit: :name', { name: skin.name })} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-2">
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <PaintBrushIcon className="size-5" weight="fill" />
                        <span className="text-sm">{t('Skins')}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{t('Edit skin')}</h1>
                    <p className="text-sm text-muted-foreground">
                        {t('Update the name or visibility for this skin.')}
                    </p>
                </div>

                <div className="border border-border bg-card p-6">
                    <SkinImage
                        src={skin.image_url}
                        alt={skin.name}
                        className="mb-6 h-32 w-24"
                        width={96}
                        height={128}
                    />
                    <Form {...update.form(skin.uuid)} className="space-y-5">
                        {({ processing, errors }) => (
                            <>
                                <div>
                                    <Label htmlFor="name">{t('Name')}</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        type="text"
                                        className="mt-1"
                                        defaultValue={skin.name}
                                        autoComplete="name"
                                        required
                                    />
                                    <InputError message={errors.name} className="mt-2" />
                                </div>
                                <div className="flex items-start gap-2">
                                    <Checkbox
                                        id="public"
                                        name="public"
                                        value="1"
                                        defaultChecked={skin.public}
                                        className="mt-0.5"
                                    />
                                    <label htmlFor="public" className="text-sm text-muted-foreground">
                                        <span className="font-medium text-foreground">{t('Public')}</span>{' '}
                                        {t('Other users will be able to see this skin')}
                                    </label>
                                </div>
                                <InputError message={errors.public} />
                                <Button type="submit" variant="default" disabled={processing}>
                                    <FloppyDiskIcon data-icon="inline-start" weight="bold" />
                                    {t('Save')}
                                </Button>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </>
    );
}
