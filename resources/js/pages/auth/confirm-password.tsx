import { Form, Head } from '@inertiajs/react';
import { LockIcon } from '@phosphor-icons/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/ConfirmablePasswordController';
import InputError from '@/components/input-error';
import { Login7 } from '@/components/login7';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslations } from '@/hooks/use-translations';

export default function ConfirmPassword() {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Confirm Password')} />

            <Login7>
                <div className="grid gap-4">
                    <p className="text-sm text-muted-foreground">
                        {t('This is a secure area of the application. Please confirm your password before continuing.')}
                    </p>

                    <Form {...store.form()} resetOnSuccess={['password']} className="grid gap-4">
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="password">{t('Password')}</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        name="password"
                                        required
                                        autoFocus
                                        autoComplete="current-password"
                                    />
                                    <InputError message={errors.password} />
                                </div>

                                <Button type="submit" className="w-full" disabled={processing}>
                                    <LockIcon data-icon="inline-start" />
                                    {t('Confirm')}
                                </Button>
                            </>
                        )}
                    </Form>
                </div>
            </Login7>
        </>
    );
}

/**
 * Login 7 supplies its own page chrome, so skip the shared auth card layout.
 */
ConfirmPassword.layout = () => null;
