import { Form, Head } from '@inertiajs/react';
import { useState } from 'react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/TwoFactorAuthenticatedSessionController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function TwoFactorChallenge() {
    const [recovery, setRecovery] = useState(false);

    return (
        <>
            <Head title="Two-factor confirmation" />

            <div className="mb-4 text-sm text-slate-600 dark:text-slate-300">
                {recovery
                    ? 'Please confirm access to your account by entering one of your emergency recovery codes.'
                    : 'Please confirm access to your account by entering the authentication code provided by your authenticator application.'}
            </div>

            <Form {...store.form()} className="flex flex-col gap-4">
                {({ processing, errors }) => (
                    <>
                        {recovery ? (
                            <div className="grid gap-2">
                                <Label htmlFor="recovery_code">Recovery Code</Label>
                                <Input id="recovery_code" name="recovery_code" autoFocus autoComplete="one-time-code" />
                                <InputError message={errors.recovery_code} />
                            </div>
                        ) : (
                            <div className="grid gap-2">
                                <Label htmlFor="code">Code</Label>
                                <Input id="code" name="code" inputMode="numeric" autoFocus autoComplete="one-time-code" />
                                <InputError message={errors.code} />
                            </div>
                        )}

                        <div className="flex items-center justify-between">
                            <button
                                type="button"
                                className="text-sm text-slate-600 underline dark:text-slate-300"
                                onClick={() => setRecovery((value) => ! value)}
                            >
                                {recovery ? 'Use an authentication code' : 'Use a recovery code'}
                            </button>

                            <Button type="submit" variant="brand" disabled={processing}>
                                Log in
                            </Button>
                        </div>
                    </>
                )}
            </Form>
        </>
    );
}
