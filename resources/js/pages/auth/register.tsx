import { Form, Head, Link } from '@inertiajs/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/RegisteredUserController';
import InputError from '@/components/input-error';
import { Login7 } from '@/components/login7';
import { PasswordInput } from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { login } from '@/routes';
import { show as policyShow } from '@/routes/policy';
import { show as termsShow } from '@/routes/terms';

type Props = {
    hasTermsAndPrivacyPolicyFeature: boolean;
};

function RequiredMark() {
    return (
        <>
            <span className="text-destructive" aria-hidden="true">
                *
            </span>
            <span className="sr-only">(required)</span>
        </>
    );
}

export default function Register({ hasTermsAndPrivacyPolicyFeature }: Props) {
    return (
        <>
            <Head title="Register" />

            <Login7
                footer={
                    <div className="mx-auto flex gap-1 text-sm">
                        <p>Already have an account?</p>
                        <Link href={login()} className="underline">
                            Log in
                        </Link>
                    </div>
                }
            >
                <Form {...store.form()} resetOnSuccess={['password', 'password_confirmation']}>
                    {({ processing, errors }) => (
                        <FieldGroup className="gap-4">
                            <FieldDescription>
                                Required fields are marked with <span className="text-destructive">*</span>.
                            </FieldDescription>

                            <Field data-invalid={errors.name ? true : undefined}>
                                <FieldLabel htmlFor="name">
                                    Name
                                    <RequiredMark />
                                </FieldLabel>
                                <Input
                                    id="name"
                                    name="name"
                                    placeholder="Your name"
                                    required
                                    autoFocus
                                    autoComplete="name"
                                    aria-invalid={errors.name ? true : undefined}
                                />
                                <InputError message={errors.name} />
                            </Field>

                            <Field data-invalid={errors.username ? true : undefined}>
                                <FieldLabel htmlFor="username">
                                    Username
                                    <RequiredMark />
                                </FieldLabel>
                                <Input
                                    id="username"
                                    name="username"
                                    placeholder="Choose a username"
                                    required
                                    autoComplete="username"
                                    aria-invalid={errors.username ? true : undefined}
                                />
                                <InputError message={errors.username} />
                            </Field>

                            <Field data-invalid={errors.email ? true : undefined}>
                                <FieldLabel htmlFor="email">
                                    Email
                                    <RequiredMark />
                                </FieldLabel>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder="m@example.com"
                                    required
                                    autoComplete="email"
                                    aria-invalid={errors.email ? true : undefined}
                                />
                                <InputError message={errors.email} />
                            </Field>

                            <Field data-invalid={errors.password ? true : undefined}>
                                <FieldLabel htmlFor="password">
                                    Password
                                    <RequiredMark />
                                </FieldLabel>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    required
                                    autoComplete="new-password"
                                    aria-invalid={errors.password ? true : undefined}
                                />
                                <InputError message={errors.password} />
                            </Field>

                            <Field data-invalid={errors.password_confirmation ? true : undefined}>
                                <FieldLabel htmlFor="password_confirmation">
                                    Confirm password
                                    <RequiredMark />
                                </FieldLabel>
                                <PasswordInput
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirm your password"
                                    required
                                    autoComplete="new-password"
                                    aria-invalid={errors.password_confirmation ? true : undefined}
                                />
                                <InputError message={errors.password_confirmation} />
                            </Field>

                            {hasTermsAndPrivacyPolicyFeature && (
                                <Field orientation="horizontal" data-invalid={errors.terms ? true : undefined}>
                                    <Checkbox
                                        id="terms"
                                        name="terms"
                                        value="1"
                                        required
                                        className="mt-0.5"
                                        aria-invalid={errors.terms ? true : undefined}
                                    />
                                    <div className="grid gap-1.5">
                                        <label
                                            htmlFor="terms"
                                            className="block text-xs leading-5 text-muted-foreground select-none"
                                        >
                                            I agree to the{' '}
                                            <a
                                                href={termsShow.url()}
                                                target="_blank"
                                                rel="noreferrer"
                                                className="font-medium text-foreground underline underline-offset-4 hover:text-primary"
                                            >
                                                Terms and Conditions
                                            </a>{' '}
                                            and{' '}
                                            <a
                                                href={policyShow.url()}
                                                target="_blank"
                                                rel="noreferrer"
                                                className="font-medium text-foreground underline underline-offset-4 hover:text-primary"
                                            >
                                                Privacy Policy
                                            </a>
                                            {' '}
                                            <RequiredMark />
                                        </label>
                                        <InputError message={errors.terms} />
                                    </div>
                                </Field>
                            )}

                            <Button type="submit" className="w-full" disabled={processing}>
                                Register
                            </Button>
                        </FieldGroup>
                    )}
                </Form>
            </Login7>
        </>
    );
}

/**
 * Login 7 supplies its own page chrome, so skip the shared auth card layout.
 */
Register.layout = () => null;
