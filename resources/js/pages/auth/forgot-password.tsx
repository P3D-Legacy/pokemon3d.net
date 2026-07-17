import { Form, Head } from '@inertiajs/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/PasswordResetLinkController';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';

type Props = {
    status?: string;
};

export default function ForgotPassword({ status }: Props) {
    return (
        <>
            <Head title="Forgot password" />

            <div className="mb-4 text-sm text-slate-600 dark:text-slate-300">
                Forgot your password? Enter your email and we will email you a password reset link.
            </div>

            {status && <div className="mb-4 text-sm font-medium text-green-600">{status}</div>}

            <Form {...store.form()} className="flex flex-col gap-4">
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="email">Email</Label>
                            <Input id="email" type="email" name="email" required autoFocus autoComplete="email" />
                            <InputError message={errors.email} />
                        </div>

                        <div className="flex items-center justify-between">
                            <TextLink href={login()}>Back to login</TextLink>
                            <Button type="submit" variant="brand" disabled={processing}>
                                Email password reset link
                            </Button>
                        </div>
                    </>
                )}
            </Form>
        </>
    );
}
