import { Form, Head } from '@inertiajs/react';
import { SignInIcon } from '@phosphor-icons/react';
import { useState } from 'react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/TwoFactorAuthenticatedSessionController';
import InputError from '@/components/input-error';
import { Login7 } from '@/components/login7';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function TwoFactorChallenge() {
    const [recovery, setRecovery] = useState(false);

    return (
        <>
            <Head title="Two-factor confirmation" />

            <Login7>
                <div className="grid gap-4">
                    <p className="text-sm text-muted-foreground">
                        {recovery
                            ? 'Please confirm access to your account by entering one of your emergency recovery codes.'
                            : 'Please confirm access to your account by entering the authentication code provided by your authenticator application.'}
                    </p>

                    <Form {...store.form()} className="grid gap-4">
                        {({ processing, errors }) => (
                            <>
                                {recovery ? (
                                    <div className="grid gap-2">
                                        <Label htmlFor="recovery_code">Recovery Code</Label>
                                        <Input
                                            id="recovery_code"
                                            name="recovery_code"
                                            autoFocus
                                            autoComplete="one-time-code"
                                        />
                                        <InputError message={errors.recovery_code} />
                                    </div>
                                ) : (
                                    <div className="grid gap-2">
                                        <Label htmlFor="code">Code</Label>
                                        <Input
                                            id="code"
                                            name="code"
                                            inputMode="numeric"
                                            autoFocus
                                            autoComplete="one-time-code"
                                        />
                                        <InputError message={errors.code} />
                                    </div>
                                )}

                                <Button type="submit" className="w-full" disabled={processing}>
                                    <SignInIcon data-icon="inline-start" />
                                    Log in
                                </Button>

                                <button
                                    type="button"
                                    className="text-center text-sm text-muted-foreground underline"
                                    onClick={() => setRecovery((value) => ! value)}
                                >
                                    {recovery ? 'Use an authentication code' : 'Use a recovery code'}
                                </button>
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
TwoFactorChallenge.layout = () => null;
