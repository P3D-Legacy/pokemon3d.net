import { Form, Head } from '@inertiajs/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/RegisteredUserController';
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { show as policyShow } from '@/routes/policy';
import { show as termsShow } from '@/routes/terms';

type Props = {
    hasTermsAndPrivacyPolicyFeature: boolean;
};

export default function Register({ hasTermsAndPrivacyPolicyFeature }: Props) {
    return (
        <>
            <Head title="Register" />

            <Form {...store.form()} resetOnSuccess={['password', 'password_confirmation']} className="flex flex-col gap-4">
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>
                            <Input id="name" name="name" required autoFocus autoComplete="name" />
                            <InputError message={errors.name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="username">Username</Label>
                            <Input id="username" name="username" required autoComplete="username" />
                            <InputError message={errors.username} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="email">Email</Label>
                            <Input id="email" type="email" name="email" required autoComplete="email" />
                            <InputError message={errors.email} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="password">Password</Label>
                            <Input id="password" type="password" name="password" required autoComplete="new-password" />
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

                        {hasTermsAndPrivacyPolicyFeature && (
                            <div className="flex items-start gap-3">
                                <input
                                    id="terms"
                                    name="terms"
                                    type="checkbox"
                                    value="1"
                                    required
                                    className="mt-0.5 size-4 rounded border-slate-300 text-green-600 shadow-xs focus:ring-green-500"
                                />
                                <Label htmlFor="terms" className="leading-5">
                                    I agree to the{' '}
                                    <a href={termsShow.url()} target="_blank" rel="noreferrer" className="underline">
                                        Terms and Conditions
                                    </a>{' '}
                                    and{' '}
                                    <a href={policyShow.url()} target="_blank" rel="noreferrer" className="underline">
                                        Privacy Policy
                                    </a>
                                </Label>
                            </div>
                        )}

                        <div className="flex items-center justify-between">
                            <TextLink href={login()}>Already registered?</TextLink>
                            <Button type="submit" variant="brand" disabled={processing}>
                                Register
                            </Button>
                        </div>
                    </>
                )}
            </Form>
        </>
    );
}
