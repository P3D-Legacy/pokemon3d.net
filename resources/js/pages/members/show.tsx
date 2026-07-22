import { Head, usePage } from '@inertiajs/react';
import { useState } from 'react';

import { DetailsPanel, type PlayerDetails } from '@/components/details-panel';
import { PokedexPanel, type PokedexEntry } from '@/components/pokedex-panel';
import { TrophiesPanel, type TrophyItem } from '@/components/trophies-panel';
import { Badge } from '@/components/ui/badge';
import { UserProfile12 } from '@/components/user-profile12';
import { show as profileShow } from '@/routes/profile';
import type { SharedPageProps } from '@/types';

type Props = {
    member: {
        id: number;
        username: string;
        profile_photo_url: string;
        achievements: Array<{ name: string; description?: string | null }>;
        about: {
            name: string | null;
            joined: string | null;
            last_online: string | null;
            birthday: { date: string | null; age: string | null } | null;
            location: string | null;
            gender: string | null;
            about: string | null;
        };
        accounts: {
            gamejolt: { username: string; url: string } | null;
            discord: { label: string; url: string } | null;
            twitch: { username: string; url: string } | null;
            facebook: { name: string } | null;
        };
        gameSave: {
            available: boolean;
            message?: string;
            last_synced?: string;
            caught_count?: number;
            seen_count?: number;
            party?: unknown;
            details?: PlayerDetails;
            pokedex?: PokedexEntry[];
            statistics?: Record<string, unknown> | unknown[];
            trophies?: {
                achieved: number;
                total: number;
                items: TrophyItem[];
            };
        };
    };
};

export default function MembersShow({ member }: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const [aboutTab, setAboutTab] = useState<'about' | 'accounts'>('about');
    const [saveTab, setSaveTab] = useState('party');

    const isOwnProfile = auth.user?.id === member.id;
    const displayName = member.about.name ?? member.username;
    const stats = [
        { label: 'Achievements', value: String(member.achievements.length) },
        ...(member.gameSave.available
            ? [
                  { label: 'Caught', value: String(member.gameSave.caught_count ?? 0) },
                  { label: 'Seen', value: String(member.gameSave.seen_count ?? 0) },
              ]
            : []),
    ];

    return (
        <>
            <Head title={member.username} />

            <div className="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 py-10 md:grid-cols-6 sm:px-6">
                <div className="md:col-span-2">
                    <UserProfile12
                        className="max-w-none"
                        coverClassName="bg-spring bg-primary"
                        editHref={isOwnProfile ? profileShow.url() : null}
                        showFollowActions={false}
                        user={{
                            name: displayName,
                            username: `@${member.username}`,
                            avatar: member.profile_photo_url,
                            bio: member.about.about ?? undefined,
                            verified: member.achievements.length > 0,
                        }}
                        stats={stats}
                    >
                        {member.achievements.length > 0 ? (
                            <div className="mt-5 flex flex-wrap gap-2">
                                {member.achievements.map((achievement) => (
                                    <Badge key={achievement.name} variant="secondary">
                                        {achievement.name}
                                    </Badge>
                                ))}
                            </div>
                        ) : null}

                        <div className="mt-6 flex gap-4 border-b text-sm">
                            <button
                                type="button"
                                className={`pb-2 ${aboutTab === 'about' ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground'}`}
                                onClick={() => setAboutTab('about')}
                            >
                                About
                            </button>
                            <button
                                type="button"
                                className={`pb-2 ${aboutTab === 'accounts' ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground'}`}
                                onClick={() => setAboutTab('accounts')}
                            >
                                Connected Accounts
                            </button>
                        </div>

                        <dl className="mt-4 flex flex-col gap-3 text-sm">
                            {aboutTab === 'about' ? (
                                <>
                                    <Detail title="Full name" value={member.about.name} />
                                    <Detail title="Joined" value={member.about.joined} />
                                    <Detail title="Last online" value={member.about.last_online} />
                                    {member.about.birthday ? (
                                        <div>
                                            <dt className="text-muted-foreground">Birthday</dt>
                                            <dd>{member.about.birthday.date}</dd>
                                            <dd>{member.about.birthday.age}</dd>
                                        </div>
                                    ) : null}
                                    <Detail title="Location" value={member.about.location} />
                                    <Detail title="Gender" value={member.about.gender} />
                                </>
                            ) : (
                                <>
                                    {member.accounts.gamejolt ? (
                                        <div>
                                            <dt className="text-muted-foreground">Game Jolt</dt>
                                            <dd>
                                                <a
                                                    href={member.accounts.gamejolt.url}
                                                    className="text-primary hover:underline"
                                                    target="_blank"
                                                    rel="noreferrer"
                                                >
                                                    {member.accounts.gamejolt.username}
                                                </a>
                                            </dd>
                                        </div>
                                    ) : null}
                                    {member.accounts.discord ? (
                                        <div>
                                            <dt className="text-muted-foreground">Discord</dt>
                                            <dd>
                                                <a
                                                    href={member.accounts.discord.url}
                                                    className="text-primary hover:underline"
                                                    target="_blank"
                                                    rel="noreferrer"
                                                >
                                                    {member.accounts.discord.label}
                                                </a>
                                            </dd>
                                        </div>
                                    ) : null}
                                    {member.accounts.twitch ? (
                                        <div>
                                            <dt className="text-muted-foreground">Twitch</dt>
                                            <dd>
                                                <a
                                                    href={member.accounts.twitch.url}
                                                    className="text-primary hover:underline"
                                                    target="_blank"
                                                    rel="noreferrer"
                                                >
                                                    {member.accounts.twitch.username}
                                                </a>
                                            </dd>
                                        </div>
                                    ) : null}
                                    {member.accounts.facebook ? (
                                        <Detail title="Facebook" value={member.accounts.facebook.name} />
                                    ) : null}
                                    {!member.accounts.gamejolt &&
                                    !member.accounts.discord &&
                                    !member.accounts.twitch &&
                                    !member.accounts.facebook ? (
                                        <p className="text-muted-foreground">No connected accounts</p>
                                    ) : null}
                                </>
                            )}
                        </dl>
                    </UserProfile12>
                </div>

                <div className="md:col-span-4">
                    <div className="border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                        <h2 className="text-3xl font-semibold text-slate-800 dark:text-slate-200">Game Save</h2>
                        {!member.gameSave.available ? (
                            <p className="mt-3 text-sm text-slate-500">{member.gameSave.message}</p>
                        ) : (
                            <>
                                <p className="mt-2 text-sm text-slate-500">Last synced: {member.gameSave.last_synced}</p>
                                <div className="mt-4 flex flex-wrap gap-3 border-b border-slate-100 text-sm dark:border-slate-800">
                                    {[
                                        ['party', 'Party'],
                                        ['details', 'Details'],
                                        ['pokedex', 'Pokédex'],
                                        ['trophies', 'Trophies'],
                                        ['statistics', 'Statistics'],
                                    ].map(([key, label]) => (
                                        <button
                                            key={key}
                                            type="button"
                                            className={`pb-2 ${saveTab === key ? 'border-b-2 border-green-500 text-green-500' : 'text-slate-500'}`}
                                            onClick={() => setSaveTab(key)}
                                        >
                                            {label}
                                        </button>
                                    ))}
                                </div>
                                <div className="mt-4 text-sm text-slate-700 dark:text-slate-200">
                                    {saveTab === 'party' && <JsonBlock value={member.gameSave.party} />}
                                    {saveTab === 'details' && <DetailsPanel details={member.gameSave.details ?? {}} />}
                                    {saveTab === 'pokedex' && (
                                        <PokedexPanel
                                            pokedex={member.gameSave.pokedex ?? []}
                                            caughtCount={member.gameSave.caught_count ?? 0}
                                            seenCount={member.gameSave.seen_count ?? 0}
                                        />
                                    )}
                                    {saveTab === 'trophies' && (
                                        <TrophiesPanel
                                            trophies={member.gameSave.trophies?.items ?? []}
                                            achievedCount={member.gameSave.trophies?.achieved ?? 0}
                                            totalCount={member.gameSave.trophies?.total ?? 0}
                                        />
                                    )}
                                    {saveTab === 'statistics' && <JsonBlock value={member.gameSave.statistics} />}
                                </div>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}

function Detail({ title, value }: { title: string; value?: string | null }) {
    if (!value) {
        return null;
    }

    return (
        <div>
            <dt className="text-muted-foreground">{title}</dt>
            <dd>{value}</dd>
        </div>
    );
}

function JsonBlock({ value }: { value: unknown }) {
    if (value == null) {
        return <p className="text-slate-500">No data</p>;
    }

    if (Array.isArray(value)) {
        return (
            <ul className="flex flex-col gap-2">
                {value.map((item, index) => (
                    <li key={index} className="rounded border border-slate-200 p-2 dark:border-slate-700">
                        <pre className="whitespace-pre-wrap text-xs">{typeof item === 'string' ? item : JSON.stringify(item, null, 2)}</pre>
                    </li>
                ))}
            </ul>
        );
    }

    if (typeof value === 'object') {
        return (
            <dl className="grid gap-2 sm:grid-cols-2">
                {Object.entries(value as Record<string, unknown>).map(([key, entry]) => (
                    <div key={key} className="rounded border border-slate-200 p-2 dark:border-slate-700">
                        <dt className="text-xs uppercase text-slate-500">{key}</dt>
                        <dd className="text-sm">{typeof entry === 'string' || typeof entry === 'number' ? String(entry) : JSON.stringify(entry)}</dd>
                    </div>
                ))}
            </dl>
        );
    }

    return <p>{String(value)}</p>;
}
