import { Head } from '@inertiajs/react';
import { useState } from 'react';

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
            details?: Record<string, unknown> | unknown[];
            pokedex?: unknown[];
            statistics?: Record<string, unknown> | unknown[];
            trophies?: {
                achieved: number;
                total: number;
                items: Array<{ title: string; achieved: boolean; description?: string | null }>;
            };
        };
    };
};

export default function MembersShow({ member }: Props) {
    const [aboutTab, setAboutTab] = useState<'about' | 'accounts'>('about');
    const [saveTab, setSaveTab] = useState('party');

    return (
        <>
            <Head title={member.username} />

            <div className="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 py-10 md:grid-cols-6 sm:px-6">
                <div className="md:col-span-2">
                    <div className="h-48 rounded-t-lg bg-green-600 bg-spring" />
                    <div className="relative -mt-20 ml-5">
                        <img
                            src={member.profile_photo_url}
                            alt={member.username}
                            className="size-36 rounded-lg border border-slate-400 object-cover shadow"
                        />
                    </div>
                    <div className="rounded-b-lg border border-t-0 border-slate-300 bg-white p-5 pt-6 dark:border-slate-800 dark:bg-slate-900">
                        <h1 className="text-3xl font-semibold text-slate-800 dark:text-slate-200">{member.username}</h1>
                        <div className="mt-3 flex flex-wrap gap-2 text-xs text-slate-500">
                            {member.achievements.map((achievement) => (
                                <span key={achievement.name} className="rounded bg-slate-100 px-2 py-1 dark:bg-slate-800">
                                    {achievement.name}
                                </span>
                            ))}
                        </div>

                        <div className="mt-4 flex gap-4 border-b border-slate-100 text-sm dark:border-slate-800">
                            <button
                                type="button"
                                className={`pb-2 ${aboutTab === 'about' ? 'border-b-2 border-green-500 text-green-500' : 'text-slate-500'}`}
                                onClick={() => setAboutTab('about')}
                            >
                                About
                            </button>
                            <button
                                type="button"
                                className={`pb-2 ${aboutTab === 'accounts' ? 'border-b-2 border-green-500 text-green-500' : 'text-slate-500'}`}
                                onClick={() => setAboutTab('accounts')}
                            >
                                Connected Accounts
                            </button>
                        </div>

                        <dl className="mt-4 space-y-3 text-sm">
                            {aboutTab === 'about' ? (
                                <>
                                    <Detail title="Full name" value={member.about.name} />
                                    <Detail title="Joined" value={member.about.joined} />
                                    <Detail title="Last online" value={member.about.last_online} />
                                    {member.about.birthday && (
                                        <div>
                                            <dt className="text-slate-500">Birthday</dt>
                                            <dd>{member.about.birthday.date}</dd>
                                            <dd>{member.about.birthday.age}</dd>
                                        </div>
                                    )}
                                    <Detail title="Location" value={member.about.location} />
                                    <Detail title="Gender" value={member.about.gender} />
                                    <Detail title="About" value={member.about.about} />
                                </>
                            ) : (
                                <>
                                    {member.accounts.gamejolt && (
                                        <div>
                                            <dt className="text-slate-500">Game Jolt</dt>
                                            <dd>
                                                <a href={member.accounts.gamejolt.url} className="text-green-600 hover:underline" target="_blank" rel="noreferrer">
                                                    {member.accounts.gamejolt.username}
                                                </a>
                                            </dd>
                                        </div>
                                    )}
                                    {member.accounts.discord && (
                                        <div>
                                            <dt className="text-slate-500">Discord</dt>
                                            <dd>
                                                <a href={member.accounts.discord.url} className="text-green-600 hover:underline" target="_blank" rel="noreferrer">
                                                    {member.accounts.discord.label}
                                                </a>
                                            </dd>
                                        </div>
                                    )}
                                    {member.accounts.twitch && (
                                        <div>
                                            <dt className="text-slate-500">Twitch</dt>
                                            <dd>
                                                <a href={member.accounts.twitch.url} className="text-green-600 hover:underline" target="_blank" rel="noreferrer">
                                                    {member.accounts.twitch.username}
                                                </a>
                                            </dd>
                                        </div>
                                    )}
                                    {member.accounts.facebook && <Detail title="Facebook" value={member.accounts.facebook.name} />}
                                </>
                            )}
                        </dl>
                    </div>
                </div>

                <div className="md:col-span-4">
                    <div className="rounded-lg border border-slate-300 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                        <h2 className="text-3xl font-semibold text-slate-800 dark:text-slate-200">Game Save</h2>
                        {! member.gameSave.available ? (
                            <p className="mt-3 text-sm text-slate-500">{member.gameSave.message}</p>
                        ) : (
                            <>
                                <p className="mt-2 text-sm text-slate-500">Last synced: {member.gameSave.last_synced}</p>
                                <div className="mt-4 flex flex-wrap gap-3 border-b border-slate-100 text-sm dark:border-slate-800">
                                    {[
                                        ['party', 'Party'],
                                        ['details', 'Details'],
                                        ['pokedex', `Pokédex (Caught: ${member.gameSave.caught_count} / Seen: ${member.gameSave.seen_count})`],
                                        ['trophies', `In-Game Trophies (${member.gameSave.trophies?.achieved}/${member.gameSave.trophies?.total})`],
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
                                    {saveTab === 'details' && <JsonBlock value={member.gameSave.details} />}
                                    {saveTab === 'pokedex' && <JsonBlock value={member.gameSave.pokedex} />}
                                    {saveTab === 'trophies' && (
                                        <ul className="space-y-2">
                                            {member.gameSave.trophies?.items.map((trophy) => (
                                                <li key={trophy.title} className={trophy.achieved ? 'text-green-600' : 'text-slate-400'}>
                                                    {trophy.title}
                                                </li>
                                            ))}
                                        </ul>
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
    if (! value) {
        return null;
    }

    return (
        <div>
            <dt className="text-slate-500">{title}</dt>
            <dd className="text-slate-800 dark:text-slate-100">{value}</dd>
        </div>
    );
}

function JsonBlock({ value }: { value: unknown }) {
    if (value == null) {
        return <p className="text-slate-500">No data</p>;
    }

    if (Array.isArray(value)) {
        return (
            <ul className="space-y-2">
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
