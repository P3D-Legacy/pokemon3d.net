import { Form, Head, Link, usePage } from '@inertiajs/react';
import {
    ChatCircleIcon,
    DiscordLogoIcon,
    FacebookLogoIcon,
    GameControllerIcon,
    SignInIcon,
    TwitchLogoIcon,
} from '@phosphor-icons/react';
import type { ReactNode } from 'react';
import { useState } from 'react';

import { store } from '@/actions/Laravel/Fortify/Http/Controllers/AuthenticatedSessionController';
import InputError from '@/components/input-error';
import { Login7 } from '@/components/login7';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { register } from '@/routes';
import { login as discordLogin } from '@/routes/discord';
import { login as facebookLogin } from '@/routes/facebook';
import { login as forumLogin } from '@/routes/forum';
import { login as gamejoltLogin } from '@/routes/gamejolt';
import { request as passwordRequest } from '@/routes/password';
import { login as twitchLogin } from '@/routes/twitch';
import type { SharedPageProps } from '@/types';

type Props = {
    canResetPassword: boolean;
    status?: string;
};

function SocialButton({
    href,
    className,
    children,
}: {
    href: string;
    className?: string;
    children: ReactNode;
}) {
    return (
        <Button variant="outline" className={cn('w-full justify-center gap-2 border-transparent text-white', className)} asChild>
            <a href={href}>{children}</a>
        </Button>
    );
}

export default function Login({ canResetPassword, status }: Props) {
    const { flash, socialLogin } = usePage<SharedPageProps>().props;
    const [forumOpen, setForumOpen] = useState(false);
    const [gamejoltOpen, setGamejoltOpen] = useState(false);

    const showSocial =
        socialLogin.discord ||
        socialLogin.facebook ||
        socialLogin.twitch ||
        socialLogin.gamejolt ||
        socialLogin.xenforo;

    return (
        <>
            <Head title="Log in" />

            <Login7
                footer={
                    <div className="mx-auto flex gap-1 text-sm">
                        <p>Don&apos;t have an account yet?</p>
                        <Link href={register()} className="underline">
                            Register
                        </Link>
                    </div>
                }
            >
                <div className="grid gap-4">
                    {(status || flash.status) && (
                        <div className="text-sm font-medium text-green-600">{status || flash.status}</div>
                    )}

                    {flash.error && <div className="text-sm font-medium text-red-600">{flash.error}</div>}

                    <Form {...store.form()} resetOnSuccess={['password']} className="grid gap-4">
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="username">Email or Username</Label>
                                    <Input
                                        id="username"
                                        name="username"
                                        type="text"
                                        placeholder="ash.ketchum@example.com"
                                        required
                                        autoFocus
                                        autoComplete="username"
                                    />
                                    <InputError message={errors.username} />
                                </div>

                                <div className="grid gap-2">
                                    <div className="flex items-center justify-between gap-2">
                                        <Label htmlFor="password">Password</Label>
                                        {canResetPassword && (
                                            <TextLink href={passwordRequest()} tabIndex={5} className="text-xs">
                                                Forgot your password?
                                            </TextLink>
                                        )}
                                    </div>
                                    <Input
                                        id="password"
                                        name="password"
                                        type="password"
                                        placeholder="Enter your password"
                                        required
                                        autoComplete="current-password"
                                    />
                                    <InputError message={errors.password} />
                                </div>

                                <div className="flex items-center gap-3">
                                    <Checkbox id="remember" name="remember" value="1" />
                                    <Label htmlFor="remember">Remember me</Label>
                                </div>

                                <Button type="submit" className="w-full" disabled={processing}>
                                    <SignInIcon data-icon="inline-start" />
                                    Log in
                                </Button>
                            </>
                        )}
                    </Form>

                    {showSocial && (
                        <>
                            <div className="flex items-center gap-4">
                                <span className="h-px w-full bg-input" />
                                <span className="text-xs text-muted-foreground">OR</span>
                                <span className="h-px w-full bg-input" />
                            </div>

                            <div className="grid gap-2">
                                {socialLogin.discord && (
                                    <SocialButton href={discordLogin.url()} className="bg-[#5865F2] hover:bg-[#4752C4]">
                                        <DiscordLogoIcon data-icon="inline-start" weight="fill" />
                                        Log in with Discord
                                    </SocialButton>
                                )}
                                {socialLogin.facebook && (
                                    <SocialButton href={facebookLogin.url()} className="bg-[#1877F2] hover:bg-[#0F5FCB]">
                                        <FacebookLogoIcon data-icon="inline-start" weight="fill" />
                                        Log in with Facebook
                                    </SocialButton>
                                )}
                                {socialLogin.twitch && (
                                    <SocialButton href={twitchLogin.url()} className="bg-[#9146FF] hover:bg-[#772CE8]">
                                        <TwitchLogoIcon data-icon="inline-start" weight="fill" />
                                        Log in with Twitch
                                    </SocialButton>
                                )}
                                {socialLogin.xenforo && (
                                    <Button
                                        type="button"
                                        variant="outline"
                                        className="w-full justify-center gap-2 border-transparent bg-blue-500 text-white hover:bg-blue-600"
                                        onClick={() => setForumOpen(true)}
                                    >
                                        <ChatCircleIcon data-icon="inline-start" weight="fill" />
                                        Log in with Forum
                                    </Button>
                                )}
                                {socialLogin.gamejolt && (
                                    <Button
                                        type="button"
                                        variant="outline"
                                        className="w-full justify-center gap-2 border-transparent bg-gamejolt-green text-black hover:bg-gamejolt-green/80 dark:bg-gamejolt-dark-green dark:text-white dark:hover:bg-gamejolt-dark-green/80"
                                        onClick={() => setGamejoltOpen(true)}
                                    >
                                        <GameControllerIcon data-icon="inline-start" weight="fill" />
                                        Log in with Game Jolt
                                    </Button>
                                )}
                            </div>

                            <p className="text-center text-xs text-muted-foreground">
                                These login methods require a P3D account associated with the social account.
                            </p>
                        </>
                    )}
                </div>
            </Login7>

            <Dialog open={forumOpen} onOpenChange={setForumOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Forum Account</DialogTitle>
                        <DialogDescription>Log in with your forum email or username and password.</DialogDescription>
                    </DialogHeader>
                    <Form
                        action={forumLogin.url()}
                        method="post"
                        className="grid gap-4"
                        onSuccess={() => setForumOpen(false)}
                    >
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="forum-username">Email or Username</Label>
                                    <Input
                                        id="forum-username"
                                        name="username"
                                        type="text"
                                        required
                                        autoComplete="username"
                                    />
                                    <InputError message={errors.username} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="forum-password">Password</Label>
                                    <Input
                                        id="forum-password"
                                        name="password"
                                        type="password"
                                        required
                                        autoComplete="current-password"
                                    />
                                    <InputError message={errors.password} />
                                </div>
                                {errors.error && <InputError message={errors.error} />}
                                <DialogFooter>
                                    <Button type="submit" disabled={processing}>
                                        <SignInIcon data-icon="inline-start" />
                                        Log in
                                    </Button>
                                </DialogFooter>
                            </>
                        )}
                    </Form>
                </DialogContent>
            </Dialog>

            <Dialog open={gamejoltOpen} onOpenChange={setGamejoltOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Game Jolt Account</DialogTitle>
                        <DialogDescription>
                            Log in with your Game Jolt username and token.{' '}
                            <a
                                href="https://gamejolt.com/help/tokens"
                                target="_blank"
                                rel="noreferrer"
                                className="underline"
                            >
                                What&apos;s my token?
                            </a>
                        </DialogDescription>
                    </DialogHeader>
                    <Form
                        action={gamejoltLogin.url()}
                        method="post"
                        className="grid gap-4"
                        onSuccess={() => setGamejoltOpen(false)}
                    >
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="gamejolt-username">Username</Label>
                                    <Input
                                        id="gamejolt-username"
                                        name="username"
                                        type="text"
                                        required
                                        autoComplete="username"
                                    />
                                    <InputError message={errors.username} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="gamejolt-token">Token</Label>
                                    <Input
                                        id="gamejolt-token"
                                        name="token"
                                        type="password"
                                        required
                                        autoComplete="current-password"
                                    />
                                    <InputError message={errors.token} />
                                </div>
                                {errors.error && <InputError message={errors.error} />}
                                <DialogFooter>
                                    <Button type="submit" disabled={processing}>
                                        <SignInIcon data-icon="inline-start" />
                                        Log in
                                    </Button>
                                </DialogFooter>
                            </>
                        )}
                    </Form>
                </DialogContent>
            </Dialog>
        </>
    );
}

/**
 * Login 7 supplies its own page chrome, so skip the shared auth card layout.
 */
Login.layout = () => null;
