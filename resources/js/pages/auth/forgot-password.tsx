import { Form, Head, Link } from '@inertiajs/react';
import { PaperPlaneTiltIcon } from '@phosphor-icons/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/PasswordResetLinkController';
import InputError from '@/components/input-error';
import { Login7 } from '@/components/login7';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslations } from '@/hooks/use-translations';
import { login } from '@/routes';

type Props = {
    status?: string;
};

export default function ForgotPassword({ status }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Forgot your password?')} />

            <Login7
                footer={
                    <div className="mx-auto flex gap-1 text-sm">
                        <p>{t('Remember your password?')}</p>
                        <Link href={login()} className="underline">
                            {t('Log in')}
                        </Link>
                    </div>
                }
            >
                <div className="grid gap-4">
                    <p className="text-sm text-muted-foreground">
                        {t(
                            'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
                        )}
                    </p>

                    {status && <div className="text-sm font-medium text-green-600">{status}</div>}

                    <Form {...store.form()} className="grid gap-4">
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="email">{t('Email')}</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        placeholder={t('ash.ketchum@example.com')}
                                        required
                                        autoFocus
                                        autoComplete="email"
                                    />
                                    <InputError message={errors.email} />
                                </div>

                                <Button type="submit" className="w-full" disabled={processing}>
                                    <PaperPlaneTiltIcon data-icon="inline-start" />
                                    {t('Email Password Reset Link')}
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
ForgotPassword.layout = () => null;
