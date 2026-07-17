import { Head, Link, router, usePage } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
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

type Props = {
    servers: ServerItem[];
    myServers: ServerItem[];
    canCreate: boolean;
};

export default function ServersIndex({ servers, myServers, canCreate }: Props) {
    const { auth } = usePage<SharedPageProps>().props;

    return (
        <>
            <Head title="Servers" />

            <div className="mx-auto max-w-7xl space-y-10 px-4 py-10 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between">
                    <h1 className="text-xl font-semibold">Servers</h1>
                    {canCreate && (
                        <Link href={create.url()}>
                            <Button variant="brand">Add server</Button>
                        </Link>
                    )}
                </div>

                {myServers.length > 0 && (
                    <section>
                        <h2 className="mb-4 text-lg font-medium">My servers</h2>
                        <div className="grid gap-4 md:grid-cols-2">
                            {myServers.map((server) => (
                                <ServerCard
                                    key={server.uuid}
                                    server={server}
                                    owned
                                    onDelete={() => router.delete(destroy.url(server.uuid))}
                                    onReactivate={() => router.post(reactivate.url(server.uuid))}
                                />
                            ))}
                        </div>
                    </section>
                )}

                <section>
                    <h2 className="mb-4 text-lg font-medium">Active servers</h2>
                    <div className="grid gap-4 md:grid-cols-2">
                        {servers.map((server) => (
                            <ServerCard key={server.uuid} server={server} owned={auth.user?.id === server.user_id} />
                        ))}
                    </div>
                </section>
            </div>
        </>
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
        <div className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-black">
            <div className="flex items-start justify-between gap-3">
                <div>
                    <h3 className="text-2xl font-bold text-slate-800 dark:text-slate-100">{server.name}</h3>
                    <p className="text-sm text-slate-500">
                        {server.host}:{server.port}
                    </p>
                    {server.description && <p className="mt-2 text-sm text-slate-600 dark:text-slate-300">{server.description}</p>}
                    <div className="mt-2 flex flex-wrap gap-2 text-xs">
                        {server.official && <span className="rounded bg-green-700 px-2 py-1 text-white">Official</span>}
                        {server.ping != null && <span className="rounded bg-slate-600 px-2 py-1 text-white">Ping {server.ping}</span>}
                        {server.last_online_at && <span className="text-slate-500">Last online {server.last_online_at}</span>}
                    </div>
                </div>
                {owned && (
                    <div className="flex flex-col gap-2">
                        {! server.active && onReactivate && (
                            <Button type="button" size="sm" variant="outline" onClick={onReactivate}>
                                Reactivate
                            </Button>
                        )}
                        <Link href={edit.url(server.uuid)}>
                            <Button type="button" size="sm" variant="outline">
                                Edit
                            </Button>
                        </Link>
                        {onDelete && (
                            <Button type="button" size="sm" variant="destructive" onClick={onDelete}>
                                Delete
                            </Button>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
}
