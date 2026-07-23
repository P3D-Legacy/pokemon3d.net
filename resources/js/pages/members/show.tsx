import { Head, usePage } from '@inertiajs/react';
import { useState } from 'react';

import { DetailsPanel, type PlayerDetails } from '@/components/details-panel';
import { PartyPanel, type PartyMember } from '@/components/party-panel';
import { PokedexPanel, type PokedexDefinition } from '@/components/pokedex-panel';
import { StatisticsPanel, type StatisticItem } from '@/components/statistics-panel';
import { TrophiesPanel, type TrophyItem } from '@/components/trophies-panel';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
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
            party?: PartyMember[];
            details?: PlayerDetails;
            pokedexes?: PokedexDefinition[];
            statistics?: StatisticItem[];
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

            <div className="mx-auto grid w-full max-w-7xl grid-cols-1 gap-6 px-4 py-10 md:grid-cols-[minmax(0,1fr)_minmax(0,2fr)] sm:px-6">
                <div className="min-w-0 w-full">
                    <UserProfile12
                        className="w-full max-w-none"
                        coverClassName="bg-spring bg-primary"
                        editHref={isOwnProfile ? profileShow.url() : null}
                        showFollowActions={false}
                        user={{
                            name: displayName,
                            username: `@${member.username}`,
                            avatar: member.profile_photo_url,
                            bio: member.about.about ?? undefined,
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

                        <dl className="mt-4 flex min-w-0 flex-col gap-3 text-sm">
                            {aboutTab === 'about' ? (
                                <>
                                    <Detail title="Full name" value={member.about.name} />
                                    <Detail title="Joined" value={member.about.joined} />
                                    <Detail title="Last online" value={member.about.last_online} />
                                    {member.about.birthday ? (
                                        <div className="min-w-0">
                                            <dt className="text-muted-foreground">Birthday</dt>
                                            <dd className="break-words">{member.about.birthday.date}</dd>
                                            <dd className="break-words">{member.about.birthday.age}</dd>
                                        </div>
                                    ) : null}
                                    <Detail title="Location" value={member.about.location} />
                                    <Detail title="Gender" value={member.about.gender} />
                                </>
                            ) : (
                                <>
                                    {member.accounts.gamejolt ? (
                                        <div className="min-w-0">
                                            <dt className="text-muted-foreground">Game Jolt</dt>
                                            <dd className="break-words">
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
                                        <div className="min-w-0">
                                            <dt className="text-muted-foreground">Discord</dt>
                                            <dd className="break-words">
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
                                        <div className="min-w-0">
                                            <dt className="text-muted-foreground">Twitch</dt>
                                            <dd className="break-words">
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

                <div className="min-w-0 w-full">
                    <Card className="h-full w-full py-0">
                        <CardContent className="p-5">
                            <h2 className="text-3xl font-semibold text-foreground">Game Save</h2>
                            {!member.gameSave.available ? (
                                <p className="mt-3 text-sm text-muted-foreground">{member.gameSave.message}</p>
                            ) : (
                                <>
                                    <p className="mt-2 text-sm text-muted-foreground">
                                        Last synced: {member.gameSave.last_synced}
                                    </p>
                                    <div className="mt-4 flex flex-wrap gap-3 border-b text-sm">
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
                                                className={`pb-2 ${saveTab === key ? 'border-b-2 border-primary text-primary' : 'text-muted-foreground'}`}
                                                onClick={() => setSaveTab(key)}
                                            >
                                                {label}
                                            </button>
                                        ))}
                                    </div>
                                    <div className="mt-4 min-w-0 w-full overflow-x-auto text-sm text-foreground">
                                        {saveTab === 'party' && <PartyPanel party={member.gameSave.party ?? []} />}
                                        {saveTab === 'details' && (
                                            <DetailsPanel details={member.gameSave.details ?? {}} />
                                        )}
                                        {saveTab === 'pokedex' && (
                                            <PokedexPanel pokedexes={member.gameSave.pokedexes ?? []} />
                                        )}
                                        {saveTab === 'trophies' && (
                                            <TrophiesPanel
                                                trophies={member.gameSave.trophies?.items ?? []}
                                                achievedCount={member.gameSave.trophies?.achieved ?? 0}
                                                totalCount={member.gameSave.trophies?.total ?? 0}
                                            />
                                        )}
                                        {saveTab === 'statistics' && (
                                            <StatisticsPanel statistics={member.gameSave.statistics ?? []} />
                                        )}
                                    </div>
                                </>
                            )}
                        </CardContent>
                    </Card>
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
        <div className="min-w-0">
            <dt className="text-muted-foreground">{title}</dt>
            <dd className="break-words">{value}</dd>
        </div>
    );
}
