import { Form, Head, Link } from '@inertiajs/react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/RegisteredUserController';
import InputError from '@/components/input-error';
import { Login7 } from '@/components/login7';
import { PasswordInput } from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { useTranslations } from '@/hooks/use-translations';
import { login } from '@/routes';
import { show as policyShow } from '@/routes/policy';
import { show as termsShow } from '@/routes/terms';

type Props = {
    hasTermsAndPrivacyPolicyFeature: boolean;
};

function RequiredMark({ label }: { label: string }) {
    return (
        <>
            <span className="text-destructive" aria-hidden="true">
                *
            </span>
            <span className="sr-only">{label}</span>
        </>
    );
}

export default function Register({ hasTermsAndPrivacyPolicyFeature }: Props) {
    const { t } = useTranslations();
    const requiredLabel = t('(required)');

    return (
        <>
            <Head title={t('Register')} />

            <Login7
                footer={
                    <div className="mx-auto flex gap-1 text-sm">
                        <p>{t('Already registered?')}</p>
                        <Link href={login()} className="underline">
                            {t('Log in')}
                        </Link>
                    </div>
                }
            >
                <Form {...store.form()} resetOnSuccess={['password', 'password_confirmation']}>
                    {({ processing, errors }) => (
                        <FieldGroup className="gap-4">
                            <FieldDescription>{t('Required fields are marked with *.')}</FieldDescription>

                            <Field data-invalid={errors.name ? true : undefined}>
                                <FieldLabel htmlFor="name">
                                    {t('Name')}
                                    <RequiredMark label={requiredLabel} />
                                </FieldLabel>
                                <Input
                                    id="name"
                                    name="name"
                                    placeholder={t('Your name')}
                                    required
                                    autoFocus
                                    autoComplete="name"
                                    aria-invalid={errors.name ? true : undefined}
                                />
                                <InputError message={errors.name} />
                            </Field>

                            <Field data-invalid={errors.username ? true : undefined}>
                                <FieldLabel htmlFor="username">
                                    {t('Username')}
                                    <RequiredMark label={requiredLabel} />
                                </FieldLabel>
                                <Input
                                    id="username"
                                    name="username"
                                    placeholder={t('Choose a username')}
                                    required
                                    autoComplete="username"
                                    aria-invalid={errors.username ? true : undefined}
                                />
                                <InputError message={errors.username} />
                            </Field>

                            <Field data-invalid={errors.email ? true : undefined}>
                                <FieldLabel htmlFor="email">
                                    {t('Email')}
                                    <RequiredMark label={requiredLabel} />
                                </FieldLabel>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder={t('ash.ketchum@example.com')}
                                    required
                                    autoComplete="email"
                                    aria-invalid={errors.email ? true : undefined}
                                />
                                <InputError message={errors.email} />
                            </Field>

                            <Field data-invalid={errors.password ? true : undefined}>
                                <FieldLabel htmlFor="password">
                                    {t('Password')}
                                    <RequiredMark label={requiredLabel} />
                                </FieldLabel>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    placeholder={t('Enter your password')}
                                    required
                                    autoComplete="new-password"
                                    aria-invalid={errors.password ? true : undefined}
                                />
                                <InputError message={errors.password} />
                            </Field>

                            <Field data-invalid={errors.password_confirmation ? true : undefined}>
                                <FieldLabel htmlFor="password_confirmation">
                                    {t('Confirm Password')}
                                    <RequiredMark label={requiredLabel} />
                                </FieldLabel>
                                <PasswordInput
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder={t('Confirm your password')}
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
                                            {t('I agree to the :terms and :privacy', {
                                                terms: '__TERMS__',
                                                privacy: '__PRIVACY__',
                                            })
                                                .split(/(__TERMS__|__PRIVACY__)/)
                                                .map((part, index) => {
                                                    if (part === '__TERMS__') {
                                                        return (
                                                            <a
                                                                key={`terms-${index}`}
                                                                href={termsShow.url()}
                                                                target="_blank"
                                                                rel="noreferrer"
                                                                className="font-medium text-foreground underline underline-offset-4 hover:text-primary"
                                                            >
                                                                {t('Terms and Conditions')}
                                                            </a>
                                                        );
                                                    }

                                                    if (part === '__PRIVACY__') {
                                                        return (
                                                            <a
                                                                key={`privacy-${index}`}
                                                                href={policyShow.url()}
                                                                target="_blank"
                                                                rel="noreferrer"
                                                                className="font-medium text-foreground underline underline-offset-4 hover:text-primary"
                                                            >
                                                                {t('Privacy Policy')}
                                                            </a>
                                                        );
                                                    }

                                                    return <span key={`text-${index}`}>{part}</span>;
                                                })}{' '}
                                            <RequiredMark label={requiredLabel} />
                                        </label>
                                        <InputError message={errors.terms} />
                                    </div>
                                </Field>
                            )}

                            <Button type="submit" className="w-full" disabled={processing}>
                                {t('Register')}
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
