import { Form, Head, Link } from '@inertiajs/react';
import { LockKeyIcon } from '@phosphor-icons/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/NewPasswordController';
import InputError from '@/components/input-error';
import { Login7 } from '@/components/login7';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';

type Props = {
    email: string;
    token: string;
};

export default function ResetPassword({ email, token }: Props) {
    return (
        <>
            <Head title="Reset password" />

            <Login7
                footer={
                    <div className="mx-auto flex gap-1 text-sm">
                        <p>Remember your password?</p>
                        <Link href={login()} className="underline">
                            Log in
                        </Link>
                    </div>
                }
            >
                <div className="grid gap-4">
                    <p className="text-sm text-muted-foreground">Choose a new password for your account.</p>

                    <Form
                        {...store.form()}
                        resetOnSuccess={['password', 'password_confirmation']}
                        className="grid gap-4"
                    >
                        {({ processing, errors }) => (
                            <>
                                <input type="hidden" name="token" value={token} />

                                <div className="grid gap-2">
                                    <Label htmlFor="email">Email</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        defaultValue={email}
                                        required
                                        autoComplete="email"
                                    />
                                    <InputError message={errors.email} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="password">Password</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        name="password"
                                        required
                                        autoFocus
                                        autoComplete="new-password"
                                    />
                                    <InputError message={errors.password} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="password_confirmation">Confirm Password</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        required
                                        autoComplete="new-password"
                                    />
                                    <InputError message={errors.password_confirmation} />
                                </div>

                                <Button type="submit" className="w-full" disabled={processing}>
                                    <LockKeyIcon data-icon="inline-start" />
                                    Reset password
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
ResetPassword.layout = () => null;
