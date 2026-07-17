import { Form, Head } from '@inertiajs/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/NewPasswordController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Props = {
    email: string;
    token: string;
};

export default function ResetPassword({ email, token }: Props) {
    return (
        <>
            <Head title="Reset password" />

            <Form {...store.form()} resetOnSuccess={['password', 'password_confirmation']} className="flex flex-col gap-4">
                {({ processing, errors }) => (
                    <>
                        <input type="hidden" name="token" value={token} />

                        <div className="grid gap-2">
                            <Label htmlFor="email">Email</Label>
                            <Input id="email" type="email" name="email" defaultValue={email} required autoComplete="email" />
                            <InputError message={errors.email} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password">Password</Label>
                            <Input id="password" type="password" name="password" required autoFocus autoComplete="new-password" />
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

                        <Button type="submit" variant="brand" className="w-full" disabled={processing}>
                            Reset password
                        </Button>
                    </>
                )}
            </Form>
        </>
    );
}
