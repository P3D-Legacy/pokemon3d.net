import { Head, Link } from '@inertiajs/react';

import type { Paginated } from '@/types';

type Member = {
    id: number;
    username: string;
    name: string;
    profile_photo_url: string;
    url: string;
};

type Props = {
    members: Paginated<Member>;
};

export default function MembersIndex({ members }: Props) {
    return (
        <>
            <Head title="Members" />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <h1 className="mb-6 text-xl font-semibold text-slate-800 dark:text-slate-200">Members</h1>
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {members.data.map((member) => (
                        <Link
                            key={member.id}
                            href={member.url}
                            className="flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm hover:border-green-500 dark:border-slate-700 dark:bg-slate-900"
                        >
                            <img src={member.profile_photo_url} alt={member.username} className="size-12 rounded-full object-cover" />
                            <div>
                                <div className="font-medium text-slate-900 dark:text-slate-100">{member.username}</div>
                                <div className="text-sm text-slate-500">{member.name}</div>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </>
    );
}
