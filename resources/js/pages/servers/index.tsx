import { Head, Link, router, usePage } from '@inertiajs/react';
import {
    CheckCircleIcon,
    CircleIcon,
    ClockIcon,
    HardDrivesIcon,
    PencilSimpleIcon,
    PlusIcon,
    PulseIcon,
    SealCheckIcon,
    TrashIcon,
    WifiHighIcon,
    WifiSlashIcon,
} from '@phosphor-icons/react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { cn } from '@/lib/utils';
import { show as profileShow } from '@/routes/profile';
import { index as saveIndex } from '@/routes/save';
import { create, destroy, edit, reactivate } from '@/routes/server';
import type { SharedPageProps } from '@/types';

type ServerItem = {
    id: number;
    uuid: string;
    name: string;
    host: string;
    port: number;
    description: string | null;
    active: boolean;
    official: boolean;
    ping: number | null;
    last_online_at: string | null;
    user_id: number;
};

type CreateRequirements = {
    has_gamejolt: boolean;
    has_game_save: boolean;
};

type Props = {
    servers: ServerItem[];
    myServers: ServerItem[];
    canCreate: boolean;
    createRequirements: CreateRequirements | null;
};

export default function ServersIndex({ servers, myServers, canCreate, createRequirements }: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const activeCount = servers.length + myServers.filter((server) => server.active).length;
    const showRequirements = Boolean(auth.user) && ! canCreate && createRequirements !== null;

    return (
        <>
            <Head title="Servers" />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-wrap items-end justify-between gap-4">
                    <div className="flex flex-col gap-2">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <HardDrivesIcon className="size-5" weight="fill" />
                            <span className="text-sm">Multiplayer</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">Servers</h1>
                        <p className="text-sm text-muted-foreground">
                            Browse community servers or manage the ones you host.
                        </p>
                    </div>

                    {canCreate ? (
                        <Button asChild>
                            <Link href={create.url()}>
                                <PlusIcon data-icon="inline-start" weight="bold" />
                                Add server
                            </Link>
                        </Button>
                    ) : null}
                </div>

                {showRequirements && createRequirements ? (
                    <div className="mb-8 border border-border bg-muted/20 px-5 py-5">
                        <h2 className="text-base font-semibold tracking-tight">Before you can add a server</h2>
                        <p className="mt-1 text-sm text-muted-foreground">
                            Link your Game Jolt account and sync a game save (saves sync after linking and signing in).
                        </p>
                        <ul className="mt-4 flex flex-col gap-3">
                            <RequirementItem
                                complete={createRequirements.has_gamejolt}
                                label="Connect your Game Jolt account"
                                href={profileShow.url()}
                                actionLabel="Open profile"
                            />
                            <RequirementItem
                                complete={createRequirements.has_game_save}
                                label="Sync a game save"
                                href={saveIndex.url()}
                                actionLabel="Open save"
                            />
                        </ul>
                    </div>
                ) : null}

                <div className="mb-8 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <StatCard
                        title="Active servers"
                        value={String(activeCount)}
                        hint="currently listed"
                        icon={WifiHighIcon}
                    />
                    <StatCard
                        title="Your servers"
                        value={String(myServers.length)}
                        hint={myServers.length === 1 ? 'server you own' : 'servers you own'}
                        icon={HardDrivesIcon}
                    />
                    <StatCard
                        title="Official"
                        value={String(
                            [...servers, ...myServers].filter((server) => server.official).length,
                        )}
                        hint="official listings"
                        icon={SealCheckIcon}
                    />
                </div>

                {myServers.length > 0 ? (
                    <section className="mb-10 flex flex-col gap-4">
                        <div className="flex flex-col gap-1">
                            <h2 className="text-lg font-semibold tracking-tight">My servers</h2>
                            <p className="text-sm text-muted-foreground">Servers you own and can manage.</p>
                        </div>
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            {myServers.map((server) => (
                                <ServerCard
                                    key={server.uuid}
                                    server={server}
                                    owned
                                    onDelete={() => {
                                        if (window.confirm(`Delete ${server.name}? This cannot be undone.`)) {
                                            router.delete(destroy.url(server.uuid), { preserveScroll: true });
                                        }
                                    }}
                                    onReactivate={() =>
                                        router.post(reactivate.url(server.uuid), {}, { preserveScroll: true })
                                    }
                                />
                            ))}
                        </div>
                    </section>
                ) : null}

                <section className="flex flex-col gap-4">
                    <div className="flex flex-col gap-1">
                        <h2 className="text-lg font-semibold tracking-tight">Active servers</h2>
                        <p className="text-sm text-muted-foreground">Public servers currently available to join.</p>
                    </div>

                    {servers.length === 0 ? (
                        <div className="flex flex-col items-center justify-center gap-3 border border-border bg-muted/20 px-6 py-16 text-center">
                            <HardDrivesIcon className="size-10 text-muted-foreground" weight="fill" />
                            <div className="text-lg font-medium">No active servers</div>
                            <p className="max-w-md text-sm text-muted-foreground">
                                There are no public servers listed right now.
                                {canCreate ? ' Add your own to get the community playing.' : ''}
                            </p>
                            {canCreate ? (
                                <Button asChild>
                                    <Link href={create.url()}>
                                        <PlusIcon data-icon="inline-start" weight="bold" />
                                        Add server
                                    </Link>
                                </Button>
                            ) : null}
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                            {servers.map((server) => (
                                <ServerCard
                                    key={server.uuid}
                                    server={server}
                                    owned={auth.user?.id === server.user_id}
                                />
                            ))}
                        </div>
                    )}
                </section>
            </div>
        </>
    );
}

function RequirementItem({
    complete,
    label,
    href,
    actionLabel,
}: {
    complete: boolean;
    label: string;
    href: string;
    actionLabel: string;
}) {
    return (
        <li className="flex flex-wrap items-center justify-between gap-3">
            <div className="flex items-center gap-2">
                {complete ? (
                    <CheckCircleIcon className="size-5 text-primary" weight="fill" />
                ) : (
                    <CircleIcon className="size-5 text-muted-foreground" />
                )}
                <span className={cn('text-sm', complete ? 'text-muted-foreground line-through' : 'text-foreground')}>
                    {label}
                </span>
            </div>
            {! complete ? (
                <Button asChild size="sm" variant="outline">
                    <Link href={href}>{actionLabel}</Link>
                </Button>
            ) : null}
        </li>
    );
}

function StatCard({
    title,
    value,
    hint,
    icon: Icon,
}: {
    title: string;
    value: string;
    hint: string;
    icon: typeof HardDrivesIcon;
}) {
    return (
        <Card>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
                <CardTitle className="text-sm font-medium text-muted-foreground">{title}</CardTitle>
                <div className="bg-primary/10 p-2 text-primary">
                    <Icon className="size-4" weight="fill" />
                </div>
            </CardHeader>
            <CardContent>
                <div className="text-3xl font-bold">{value}</div>
                <p className="mt-1 text-sm text-muted-foreground">{hint}</p>
            </CardContent>
        </Card>
    );
}

function ServerCard({
    server,
    owned = false,
    onDelete,
    onReactivate,
}: {
    server: ServerItem;
    owned?: boolean;
    onDelete?: () => void;
    onReactivate?: () => void;
}) {
    return (
        <Card className="h-full">
            <CardHeader className="flex flex-row items-start justify-between gap-3">
                <div className="min-w-0 flex-1">
                    <div className="flex flex-wrap items-center gap-2">
                        <CardTitle className="truncate text-lg font-semibold">{server.name}</CardTitle>
                        {server.official ? (
                            <Badge variant="default">
                                <SealCheckIcon data-icon="inline-start" weight="fill" />
                                Official
                            </Badge>
                        ) : null}
                        {owned ? (
                            <Badge variant={server.active ? 'secondary' : 'outline'}>
                                {server.active ? (
                                    <>
                                        <WifiHighIcon data-icon="inline-start" weight="fill" />
                                        Active
                                    </>
                                ) : (
                                    <>
                                        <WifiSlashIcon data-icon="inline-start" weight="fill" />
                                        Inactive
                                    </>
                                )}
                            </Badge>
                        ) : null}
                    </div>
                    <CardDescription className="mt-1 font-mono text-xs">
                        {server.host}:{server.port}
                    </CardDescription>
                </div>
            </CardHeader>

            <CardContent className="flex flex-col gap-3">
                {server.description ? (
                    <p className="text-sm leading-relaxed text-muted-foreground">{server.description}</p>
                ) : (
                    <p className="text-sm text-muted-foreground/70 italic">No description provided.</p>
                )}

                <div className="flex flex-wrap gap-1.5">
                    {server.ping != null ? (
                        <Badge variant="outline">
                            <PulseIcon data-icon="inline-start" weight="fill" />
                            Ping {server.ping} ms
                        </Badge>
                    ) : null}
                    {server.last_online_at ? (
                        <Badge variant="outline">
                            <ClockIcon data-icon="inline-start" weight="fill" />
                            Last online {server.last_online_at}
                        </Badge>
                    ) : null}
                </div>
            </CardContent>

            {owned ? (
                <CardFooter className={cn('flex flex-wrap gap-2 border-t')}>
                    {! server.active && onReactivate ? (
                        <Button type="button" size="sm" variant="secondary" onClick={onReactivate}>
                            <WifiHighIcon data-icon="inline-start" weight="fill" />
                            Reactivate
                        </Button>
                    ) : null}
                    <Button size="sm" variant="outline" asChild>
                        <Link href={edit.url(server.uuid)}>
                            <PencilSimpleIcon data-icon="inline-start" weight="fill" />
                            Edit
                        </Link>
                    </Button>
                    {onDelete ? (
                        <Button type="button" size="sm" variant="destructive" onClick={onDelete}>
                            <TrashIcon data-icon="inline-start" weight="fill" />
                            Delete
                        </Button>
                    ) : null}
                </CardFooter>
            ) : null}
        </Card>
    );
}
