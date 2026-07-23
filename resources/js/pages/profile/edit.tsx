import { Form, Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';

import { update as updatePassword } from '@/actions/Laravel/Fortify/Http/Controllers/PasswordController';
import { update as updateProfile } from '@/actions/Laravel/Fortify/Http/Controllers/ProfileInformationController';
import InputError from '@/components/input-error';
import SettingsSection from '@/components/settings-section';
import { Button } from '@/components/ui/button';
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
import { destroy as destroyUser } from '@/routes/current-user';
import { destroy as destroySessions } from '@/routes/other-browser-sessions';
import { update as updateConsents } from '@/routes/profile/consents';
import { update as updatePreferences } from '@/routes/profile/preferences';
import { destroy as destroySocial } from '@/routes/profile/social';
import { store as storeGamejoltSocial } from '@/routes/profile/social/gamejolt';
import type { SharedPageProps } from '@/types';

type Props = {
    profile: {
        name: string;
        username: string;
        email: string;
        gender: number;
        location: string | null;
        about: string | null;
        birthdate: string | null;
        timezone: string | null;
        created_at_utc: string;
        created_at_local: string;
        profile_photo_url: string;
        two_factor_enabled: boolean;
        email_verified_at: string | null;
    };
    sessions: Array<{
        agent: { is_desktop: boolean; platform: string; browser: string };
        ip_address: string | null;
        is_current_device: boolean;
        last_active: string;
    }>;
    preferences: Record<string, boolean>;
    consents: Array<{ key: string; text: string; given: boolean; required: boolean }>;
    socialAccounts: Record<
        string,
        {
            enabled: boolean;
            connected: boolean;
            label: string | null;
            connect_url: string | null;
            uses_credentials?: boolean;
        }
    >;
    features: {
        canUpdateProfileInformation: boolean;
        canUpdatePasswords: boolean;
        canManageTwoFactorAuthentication: boolean;
        managesProfilePhotos: boolean;
        hasAccountDeletionFeatures: boolean;
    };
    status?: string | null;
};

export default function ProfileEdit({
    profile,
    sessions,
    preferences,
    consents,
    socialAccounts,
    features,
    status,
}: Props) {
    const { flash } = usePage<SharedPageProps>().props;
    const [gamejoltOpen, setGamejoltOpen] = useState(false);

    return (
        <>
            <Head title="Edit Profile" />

            <div className="py-10">
                <div className="mx-auto max-w-7xl space-y-10 sm:px-6 lg:px-8">
                    {(status || flash.status) && (
                        <div className="rounded-md bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/40 dark:text-green-200">
                            {status || flash.status}
                        </div>
                    )}

                    {features.canUpdateProfileInformation && (
                        <SettingsSection
                            title="Profile Information"
                            description={
                                <>
                                    <p>Update your account's profile information and email address.</p>
                                    <p className="mt-2">Your timezone: {profile.timezone}</p>
                                    <p className="mt-2">
                                        Created UTC: {profile.created_at_utc}
                                        <br />
                                        Local: {profile.created_at_local}
                                    </p>
                                </>
                            }
                        >
                            <Form {...updateProfile.form()} options={{ preserveScroll: true }} className="space-y-4" encType="multipart/form-data">
                                {({ processing, errors }) => (
                                    <>
                                        {features.managesProfilePhotos && (
                                            <div className="grid gap-2">
                                                <Label htmlFor="photo">Photo</Label>
                                                <img
                                                    src={profile.profile_photo_url}
                                                    alt={profile.name}
                                                    className="size-20 rounded-full object-cover"
                                                />
                                                <Input id="photo" name="photo" type="file" accept="image/*" />
                                                <InputError message={errors.photo} />
                                            </div>
                                        )}

                                        <div className="grid gap-2">
                                            <Label htmlFor="name">Name</Label>
                                            <Input id="name" name="name" defaultValue={profile.name} required />
                                            <InputError message={errors.name} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="username">Username</Label>
                                            <Input id="username" name="username" defaultValue={profile.username} required />
                                            <InputError message={errors.username} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="email">Email</Label>
                                            <Input id="email" type="email" name="email" defaultValue={profile.email} required />
                                            <InputError message={errors.email} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="gender">Gender</Label>
                                            <select
                                                id="gender"
                                                name="gender"
                                                defaultValue={profile.gender}
                                                className="border-input h-9 w-full rounded-md border bg-transparent px-3 text-sm shadow-xs"
                                            >
                                                <option value={0}>No selection</option>
                                                <option value={1}>Male</option>
                                                <option value={2}>Female</option>
                                                <option value={3}>Genderless</option>
                                            </select>
                                            <InputError message={errors.gender} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="location">Location</Label>
                                            <Input id="location" name="location" defaultValue={profile.location ?? ''} />
                                            <InputError message={errors.location} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="about">About</Label>
                                            <Input id="about" name="about" defaultValue={profile.about ?? ''} />
                                            <InputError message={errors.about} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="birthdate">Birthdate (dd-mm-yyyy)</Label>
                                            <Input
                                                id="birthdate"
                                                name="birthdate"
                                                defaultValue={profile.birthdate ?? ''}
                                                required
                                                placeholder="01-01-2000"
                                            />
                                            <InputError message={errors.birthdate} />
                                        </div>

                                        <div className="flex justify-end">
                                            <Button type="submit" variant="default" disabled={processing}>
                                                Save
                                            </Button>
                                        </div>
                                    </>
                                )}
                            </Form>
                        </SettingsSection>
                    )}

                    <SettingsSection title="Connected Accounts" description="Link or unlink social accounts associated with your profile.">
                        <div className="space-y-4">
                            {Object.entries(socialAccounts).map(([provider, account]) => {
                                if (! account.enabled) {
                                    return null;
                                }

                                return (
                                    <div key={provider} className="flex items-center justify-between rounded border border-slate-200 px-4 py-3 dark:border-slate-700">
                                        <div>
                                            <div className="font-medium capitalize">{provider}</div>
                                            <div className="text-sm text-slate-500">
                                                {account.connected ? account.label || 'Connected' : 'Not connected'}
                                            </div>
                                        </div>
                                        {account.connected ? (
                                            <Button
                                                type="button"
                                                variant="outline"
                                                onClick={() =>
                                                    router.delete(destroySocial.url(), {
                                                        data: { provider },
                                                        preserveScroll: true,
                                                    })
                                                }
                                            >
                                                Disconnect
                                            </Button>
                                        ) : account.uses_credentials ? (
                                            <Button type="button" variant="default" onClick={() => setGamejoltOpen(true)}>
                                                Connect
                                            </Button>
                                        ) : account.connect_url ? (
                                            <a href={account.connect_url}>
                                                <Button type="button" variant="default">
                                                    Connect
                                                </Button>
                                            </a>
                                        ) : null}
                                    </div>
                                );
                            })}
                        </div>
                    </SettingsSection>

                    <Dialog open={gamejoltOpen} onOpenChange={setGamejoltOpen}>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Connect Game Jolt</DialogTitle>
                                <DialogDescription>
                                    Link your Game Jolt username and token to this account.{' '}
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
                                action={storeGamejoltSocial.url()}
                                method="post"
                                options={{ preserveScroll: true }}
                                className="grid gap-4"
                                onSuccess={() => setGamejoltOpen(false)}
                            >
                                {({ processing, errors }) => (
                                    <>
                                        <div className="grid gap-2">
                                            <Label htmlFor="profile-gamejolt-username">Username</Label>
                                            <Input
                                                id="profile-gamejolt-username"
                                                name="username"
                                                type="text"
                                                required
                                                autoComplete="username"
                                            />
                                            <InputError message={errors.username} />
                                        </div>
                                        <div className="grid gap-2">
                                            <Label htmlFor="profile-gamejolt-token">Token</Label>
                                            <Input
                                                id="profile-gamejolt-token"
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
                                                Connect
                                            </Button>
                                        </DialogFooter>
                                    </>
                                )}
                            </Form>
                        </DialogContent>
                    </Dialog>

                    <SettingsSection title="Preferences" description="Choose what to share on your public profile.">
                        <div className="space-y-3">
                            {Object.entries(preferences).map(([setting, value]) => (
                                <label key={setting} className="flex items-center gap-3">
                                    <input
                                        type="checkbox"
                                        checked={Boolean(value)}
                                        onChange={() =>
                                            router.patch(updatePreferences.url(), { setting }, { preserveScroll: true })
                                        }
                                        className="size-4 rounded border-slate-300 text-green-600"
                                    />
                                    <span className="text-sm capitalize">Show {setting}</span>
                                </label>
                            ))}
                        </div>
                    </SettingsSection>

                    <SettingsSection title="Consents" description="Manage consents required for using this website.">
                        <div className="space-y-4">
                            {consents.map((consent) => (
                                <label key={consent.key} className="flex items-start gap-3">
                                    <input
                                        type="checkbox"
                                        checked={consent.given}
                                        disabled={consent.required && consent.given}
                                        onChange={() =>
                                            router.patch(updateConsents.url(), { consent: consent.key }, { preserveScroll: true })
                                        }
                                        className="mt-1 size-4 rounded border-slate-300 text-green-600 disabled:opacity-60"
                                    />
                                    <span className="text-sm" dangerouslySetInnerHTML={{ __html: consent.text }} />
                                </label>
                            ))}
                        </div>
                    </SettingsSection>

                    {features.canUpdatePasswords && (
                        <SettingsSection title="Update Password" description="Ensure your account is using a long, random password to stay secure.">
                            <Form {...updatePassword.form()} options={{ preserveScroll: true }} resetOnSuccess className="space-y-4">
                                {({ processing, errors }) => (
                                    <>
                                        <div className="grid gap-2">
                                            <Label htmlFor="current_password">Current Password</Label>
                                            <Input id="current_password" type="password" name="current_password" autoComplete="current-password" />
                                            <InputError message={errors.current_password} />
                                        </div>
                                        <div className="grid gap-2">
                                            <Label htmlFor="password">New Password</Label>
                                            <Input id="password" type="password" name="password" autoComplete="new-password" />
                                            <InputError message={errors.password} />
                                        </div>
                                        <div className="grid gap-2">
                                            <Label htmlFor="password_confirmation">Confirm Password</Label>
                                            <Input
                                                id="password_confirmation"
                                                type="password"
                                                name="password_confirmation"
                                                autoComplete="new-password"
                                            />
                                            <InputError message={errors.password_confirmation} />
                                        </div>
                                        <div className="flex justify-end">
                                            <Button type="submit" variant="default" disabled={processing}>
                                                Save
                                            </Button>
                                        </div>
                                    </>
                                )}
                            </Form>
                        </SettingsSection>
                    )}

                    {features.canManageTwoFactorAuthentication && (
                        <SettingsSection
                            title="Two Factor Authentication"
                            description="Add additional security to your account using two factor authentication."
                        >
                            <p className="text-sm text-slate-600 dark:text-slate-300">
                                Status:{' '}
                                <strong>{profile.two_factor_enabled ? 'Enabled' : 'Disabled'}</strong>
                            </p>
                            <p className="mt-2 text-sm text-slate-500">
                                Manage 2FA enablement from your account security settings. Challenge pages are already available via Fortify.
                            </p>
                            <div className="mt-4">
                                <Link href="/user/confirm-password" className="text-sm text-green-700 underline dark:text-green-400">
                                    Confirm password to manage secure settings
                                </Link>
                            </div>
                        </SettingsSection>
                    )}

                    <SettingsSection title="Browser Sessions" description="Manage and log out your active sessions on other browsers and devices.">
                        <div className="space-y-3">
                            {sessions.length === 0 && <p className="text-sm text-slate-500">No other sessions found.</p>}
                            {sessions.map((session) => (
                                <div key={`${session.ip_address}-${session.last_active}`} className="rounded border border-slate-200 px-4 py-3 text-sm dark:border-slate-700">
                                    <div className="font-medium">
                                        {session.agent.platform} - {session.agent.browser}
                                    </div>
                                    <div className="text-slate-500">
                                        {session.ip_address} · {session.last_active}
                                        {session.is_current_device ? ' · This device' : ''}
                                    </div>
                                </div>
                            ))}
                        </div>
                        <Form {...destroySessions.form()} className="mt-4 space-y-4" options={{ preserveScroll: true }}>
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="session_password">Password</Label>
                                        <Input id="session_password" type="password" name="password" required />
                                        <InputError message={errors.password} />
                                    </div>
                                    <Button type="submit" variant="outline" disabled={processing}>
                                        Log out other browser sessions
                                    </Button>
                                </>
                            )}
                        </Form>
                    </SettingsSection>

                    {features.hasAccountDeletionFeatures && (
                        <SettingsSection title="Delete Account" description="Permanently delete your account.">
                            <Form {...destroyUser.form()} className="space-y-4">
                                {({ processing, errors }) => (
                                    <>
                                        <p className="text-sm text-slate-500">
                                            Once your account is deleted, all of its resources and data will be permanently deleted.
                                        </p>
                                        <div className="grid gap-2">
                                            <Label htmlFor="delete_password">Password</Label>
                                            <Input id="delete_password" type="password" name="password" required />
                                            <InputError message={errors.password} />
                                        </div>
                                        <Button type="submit" variant="destructive" disabled={processing}>
                                            Delete Account
                                        </Button>
                                    </>
                                )}
                            </Form>
                        </SettingsSection>
                    )}
                </div>
            </div>
        </>
    );
}
