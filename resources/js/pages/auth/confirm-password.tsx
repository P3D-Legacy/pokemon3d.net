import { Form, Head } from '@inertiajs/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/ConfirmablePasswordController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function ConfirmPassword() {
    return (
        <>
            <Head title="Confirm password" />

            <div className="mb-4 text-sm text-slate-600 dark:text-slate-300">
                This is a secure area of the application. Please confirm your password before continuing.
            </div>

            <Form {...store.form()} resetOnSuccess={['password']} className="flex flex-col gap-4">
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="password">Password</Label>
                            <Input id="password" type="password" name="password" required autoFocus autoComplete="current-password" />
                            <InputError message={errors.password} />
                        </div>

                        <Button type="submit" variant="brand" className="w-full" disabled={processing}>
                            Confirm
                        </Button>
                    </>
                )}
            </Form>
        </>
    );
}
