import { Form, Head, Link } from '@inertiajs/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/EmailVerificationNotificationController';
import { Button } from '@/components/ui/button';
import { logout } from '@/routes';

type Props = {
    status?: string;
};

export default function VerifyEmail({ status }: Props) {
    return (
        <>
            <Head title="Email verification" />

            <div className="mb-4 text-sm text-slate-600 dark:text-slate-300">
                Thanks for signing up! Before getting started, please verify your email address by clicking the link we just
                emailed to you.
            </div>

            {status === 'verification-link-sent' && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            )}

            <div className="flex items-center justify-between">
                <Form {...store.form()}>
                    {({ processing }) => (
                        <Button type="submit" variant="brand" disabled={processing}>
                            Resend verification email
                        </Button>
                    )}
                </Form>

                <Link href={logout()} method="post" as="button" className="text-sm text-slate-600 underline dark:text-slate-300">
                    Log out
                </Link>
            </div>
        </>
    );
}
