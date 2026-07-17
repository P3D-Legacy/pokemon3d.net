import { Form, Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';

import InputError from '@/components/input-error';
import SettingsSection from '@/components/settings-section';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { destroy, store, update } from '@/routes/api-tokens';
import type { SharedPageProps } from '@/types';

type Token = {
    id: number;
    name: string;
    abilities: string[];
    last_used_ago: string | null;
};

type Props = {
    tokens: Token[];
    availablePermissions: string[];
    defaultPermissions: string[];
    plainTextToken?: string | null;
};

export default function ApiTokensIndex({
    tokens,
    availablePermissions,
    defaultPermissions,
    plainTextToken,
}: Props) {
    const { flash } = usePage<SharedPageProps>().props;
    const [selectedPermissions, setSelectedPermissions] = useState<string[]>(defaultPermissions);
    const tokenValue = plainTextToken || flash.token;

    return (
        <>
            <Head title="API Tokens" />

            <div className="py-10">
                <div className="mx-auto max-w-7xl space-y-10 sm:px-6 lg:px-8">
                    {tokenValue && (
                        <div className="rounded-md bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/40 dark:text-green-200">
                            Please copy your new API token. For your security, it won't be shown again.
                            <code className="mt-2 block break-all rounded bg-white px-3 py-2 dark:bg-slate-950">{tokenValue}</code>
                        </div>
                    )}

                    <SettingsSection title="Create API Token" description="API tokens allow third-party services to authenticate with our application on your behalf.">
                        <Form
                            {...store.form()}
                            options={{ preserveScroll: true }}
                            resetOnSuccess={['name']}
                            className="space-y-4"
                            onSuccess={() => setSelectedPermissions(defaultPermissions)}
                        >
                            {({ processing, errors }) => (
                                <>
                                    <div className="grid gap-2">
                                        <Label htmlFor="name">Token Name</Label>
                                        <Input id="name" name="name" required />
                                        <InputError message={errors.name} />
                                    </div>

                                    <div className="space-y-2">
                                        <Label>Permissions</Label>
                                        {availablePermissions.map((permission) => (
                                            <label key={permission} className="flex items-center gap-2 text-sm capitalize">
                                                <input
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    value={permission}
                                                    checked={selectedPermissions.includes(permission)}
                                                    onChange={(event) => {
                                                        setSelectedPermissions((current) =>
                                                            event.target.checked
                                                                ? [...current, permission]
                                                                : current.filter((item) => item !== permission),
                                                        );
                                                    }}
                                                    className="size-4 rounded border-slate-300 text-green-600"
                                                />
                                                {permission}
                                            </label>
                                        ))}
                                    </div>

                                    <div className="flex justify-end">
                                        <Button type="submit" variant="brand" disabled={processing}>
                                            Create
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </SettingsSection>

                    {tokens.length > 0 && (
                        <SettingsSection title="Manage API Tokens" description="You may delete any of your existing tokens if they are no longer needed.">
                            <div className="space-y-4">
                                {tokens.map((token) => (
                                    <div key={token.id} className="rounded border border-slate-200 px-4 py-3 dark:border-slate-700">
                                        <div className="flex items-center justify-between gap-4">
                                            <div>
                                                <div className="font-medium">{token.name}</div>
                                                <div className="text-xs text-slate-500">
                                                    {token.last_used_ago ? `Last used ${token.last_used_ago}` : 'Never used'}
                                                </div>
                                                <div className="mt-1 text-xs capitalize text-slate-500">
                                                    {token.abilities?.join(', ')}
                                                </div>
                                            </div>
                                            <div className="flex gap-2">
                                                <Button
                                                    type="button"
                                                    variant="outline"
                                                    size="sm"
                                                    onClick={() =>
                                                        router.put(update.url(token.id), {
                                                            permissions: token.abilities,
                                                        })
                                                    }
                                                >
                                                    Refresh
                                                </Button>
                                                <Button
                                                    type="button"
                                                    variant="destructive"
                                                    size="sm"
                                                    onClick={() => router.delete(destroy.url(token.id))}
                                                >
                                                    Delete
                                                </Button>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </SettingsSection>
                    )}
                </div>
            </div>
        </>
    );
}
