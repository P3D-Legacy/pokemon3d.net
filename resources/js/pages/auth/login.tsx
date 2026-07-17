import { Form, Head, Link } from '@inertiajs/react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { store } from '@/actions/Laravel/Fortify/Http/Controllers/AuthenticatedSessionController';
import { login as discordLogin } from '@/routes/discord';
import { login as facebookLogin } from '@/routes/facebook';
import { request as passwordRequest } from '@/routes/password';
import { register } from '@/routes';
import { login as twitchLogin } from '@/routes/twitch';
import type { SharedPageProps } from '@/types';
import { usePage } from '@inertiajs/react';

type Props = {
    canResetPassword: boolean;
    status?: string;
};

export default function Login({ canResetPassword, status }: Props) {
    const { flash, socialLogin } = usePage<SharedPageProps>().props;
    const showSocial =
        socialLogin.discord || socialLogin.facebook || socialLogin.twitch || socialLogin.gamejolt || socialLogin.xenforo;

    return (
        <>
            <Head title="Log in" />

            <Link
                href={register()}
                className="mb-4 flex w-full items-center justify-center rounded-md bg-green-500 px-4 py-3 text-sm font-semibold tracking-widest text-white uppercase transition hover:bg-green-600"
            >
                Register
            </Link>

            <div className="mb-4 flex items-center justify-center text-sm text-slate-400">
                <span className="w-1/12 border-b border-slate-300 dark:border-slate-500" />
                <span className="px-2">or log in with your P3D account</span>
                <span className="w-1/12 border-b border-slate-300 dark:border-slate-500" />
            </div>

            {(status || flash.status) && (
                <div className="mb-4 text-sm font-medium text-green-600">{status || flash.status}</div>
            )}

            {flash.error && <div className="mb-4 text-sm font-medium text-red-600">{flash.error}</div>}

            <Form {...store.form()} resetOnSuccess={['password']} className="flex flex-col gap-4">
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="username">Email or Username</Label>
                            <Input id="username" name="username" type="text" required autoFocus autoComplete="username" />
                            <InputError message={errors.username} />
                        </div>

                        <div className="grid gap-2">
                            <div className="flex items-center justify-between">
                                <Label htmlFor="password">Password</Label>
                                {canResetPassword && (
                                    <TextLink href={passwordRequest()} tabIndex={5}>
                                        Forgot your password?
                                    </TextLink>
                                )}
                            </div>
                            <Input id="password" name="password" type="password" required autoComplete="current-password" />
                            <InputError message={errors.password} />
                        </div>

                        <div className="flex items-center gap-3">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                value="1"
                                className="size-4 rounded border-slate-300 text-green-600 shadow-xs focus:ring-green-500"
                            />
                            <Label htmlFor="remember">Remember me</Label>
                        </div>

                        <Button type="submit" className="w-full" variant="brand" disabled={processing}>
                            Log in
                        </Button>
                    </>
                )}
            </Form>

            {showSocial && (
                <>
                    <div className="my-4 flex items-center justify-center text-sm text-slate-400">
                        <span className="w-14 border-b border-slate-300 dark:border-slate-500" />
                        <span className="px-2">or log in with</span>
                        <span className="w-14 border-b border-slate-300 dark:border-slate-500" />
                    </div>

                    <div className="grid auto-cols-auto grid-flow-col gap-2">
                        {socialLogin.discord && (
                            <a
                                href={discordLogin.url()}
                                className="mt-2 flex w-full items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white uppercase hover:bg-indigo-700"
                            >
                                Discord
                            </a>
                        )}
                        {socialLogin.facebook && (
                            <a
                                href={facebookLogin.url()}
                                className="mt-2 flex w-full items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white uppercase hover:bg-blue-700"
                            >
                                Facebook
                            </a>
                        )}
                        {socialLogin.twitch && (
                            <a
                                href={twitchLogin.url()}
                                className="mt-2 flex w-full items-center justify-center rounded-md bg-violet-600 px-4 py-2 text-sm font-semibold text-white uppercase hover:bg-violet-700"
                            >
                                Twitch
                            </a>
                        )}
                    </div>

                    {(socialLogin.gamejolt || socialLogin.xenforo) && (
                        <p className="mt-3 text-center text-xs text-slate-400">
                            Forum and GameJolt login remain available from the classic login widgets in a later wave.
                        </p>
                    )}

                    <p className="mx-8 mt-3 text-center text-xs text-slate-400 dark:text-slate-600">
                        These login methods require a P3D account associated with the social account.
                    </p>
                </>
            )}
        </>
    );
}
