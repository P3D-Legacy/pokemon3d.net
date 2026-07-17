import { Head, Link } from '@inertiajs/react';
import { ArrowRightIcon, BooksIcon, HardDrivesIcon, ImageIcon, SmileyIcon } from '@phosphor-icons/react';
import type { ReactNode } from 'react';

type Props = {
    copy: {
        welcome: string;
        intro: string[];
        documentation: string;
        documentationBody: string;
        exploreWiki: string;
        discordBody: string;
        getDiscord: string;
        customSkin: string;
        customSkinBody: string;
        getCustomization: string;
        forum: string;
        forumBody: string;
        startBrowsing: string;
        downloadLabel: string;
    };
    links: {
        wiki: string;
        discord: string;
        skinHome: string;
        forum: string;
        download: string;
    };
};

export default function Dashboard({ copy, links }: Props) {
    return (
        <>
            <Head title="Dashboard" />

            <div className="sm:py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden border border-slate-200 shadow-xl sm:rounded-lg dark:border-slate-900">
                        <div className="border-b border-slate-200 bg-spring p-6 sm:px-20 dark:border-slate-700 dark:bg-slate-900">
                            <div className="mt-8 font-mono text-3xl font-bold tracking-tighter text-slate-50">
                                {copy.welcome}
                            </div>
                            <div className="mt-6 space-y-3 text-slate-100">
                                {copy.intro.map((paragraph) => (
                                    <p key={paragraph.slice(0, 32)} dangerouslySetInnerHTML={{ __html: paragraph }} />
                                ))}
                            </div>
                            <div className="mt-6">
                                <a
                                    href={links.download}
                                    className="inline-flex items-center rounded-lg border border-green-300 bg-green-800 px-6 py-3 font-extrabold text-white shadow-lg transition hover:bg-green-600"
                                >
                                    {copy.downloadLabel}
                                </a>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 bg-slate-200/25 md:grid-cols-2 dark:bg-slate-800">
                            <FeatureCard
                                icon={<BooksIcon className="size-8 text-slate-400" />}
                                title={copy.documentation}
                                body={copy.documentationBody}
                                href={links.wiki}
                                cta={copy.exploreWiki}
                            />
                            <FeatureCard
                                icon={<HardDrivesIcon className="size-8 text-slate-400" />}
                                title="Discord"
                                body={copy.discordBody}
                                href={links.discord}
                                cta={copy.getDiscord}
                                bordered
                            />
                            <FeatureCard
                                icon={<SmileyIcon className="size-8 text-slate-400" />}
                                title={copy.customSkin}
                                body={copy.customSkinBody}
                                href={links.skinHome}
                                cta={copy.getCustomization}
                            />
                            <FeatureCard
                                icon={<ImageIcon className="size-8 text-slate-400" />}
                                title={copy.forum}
                                body={copy.forumBody}
                                href={links.forum}
                                cta={copy.startBrowsing}
                                bordered
                            />
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

function FeatureCard({
    icon,
    title,
    body,
    href,
    cta,
    bordered = false,
}: {
    icon: ReactNode;
    title: string;
    body: string;
    href: string;
    cta: string;
    bordered?: boolean;
}) {
    return (
        <div className={`p-6 ${bordered ? 'border-t border-slate-200 md:border-t-0 md:border-l dark:border-slate-900' : 'border-t border-slate-200 dark:border-slate-900 first:border-t-0 md:first:border-t'}`}>
            <div className="flex items-center">
                {icon}
                <div className="ml-4 text-lg font-semibold leading-7 text-slate-600 dark:text-slate-300">{title}</div>
            </div>
            <div className="ml-12">
                <div className="mt-2 text-sm text-slate-500 dark:text-slate-300">{body}</div>
                <Link href={href} className="mt-3 inline-flex items-center text-sm font-semibold text-green-700 dark:text-green-500">
                    {cta}
                    <ArrowRightIcon className="ml-1 size-4" />
                </Link>
            </div>
        </div>
    );
}
