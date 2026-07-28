import { Form, Head, Link } from '@inertiajs/react';
import { EnvelopeSimpleIcon, SignOutIcon } from '@phosphor-icons/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/EmailVerificationNotificationController';
import { Login7 } from '@/components/login7';
import { Button } from '@/components/ui/button';
import { useTranslations } from '@/hooks/use-translations';
import { logout } from '@/routes';

type Props = {
    status?: string;
};

export default function VerifyEmail({ status }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Email verification')} />

            <Login7
                footer={
                    <div className="mx-auto">
                        <Link
                            href={logout.url()}
                            method="post"
                            as="button"
                            className="inline-flex items-center gap-1 text-sm underline"
                        >
                            <SignOutIcon className="size-4" />
                            {t('Log out')}
                        </Link>
                    </div>
                }
            >
                <div className="grid gap-4">
                    <p className="text-sm text-muted-foreground">
                        {t(
                            "Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.",
                        )}
                    </p>

                    {status === 'verification-link-sent' && (
                        <div className="text-sm font-medium text-green-600">
                            {t(
                                'A new verification link has been sent to the email address you provided during registration.',
                            )}
                        </div>
                    )}

                    <Form {...store.form()}>
                        {({ processing }) => (
                            <Button type="submit" className="w-full" disabled={processing}>
                                <EnvelopeSimpleIcon data-icon="inline-start" />
                                {t('Resend Verification Email')}
                            </Button>
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
VerifyEmail.layout = () => null;
