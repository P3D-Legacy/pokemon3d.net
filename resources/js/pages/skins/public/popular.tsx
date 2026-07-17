import { Head, Link, usePage } from '@inertiajs/react';

import SkinCard, { type SkinCardData } from '@/components/skin-card';
import { skinsNewest, skinsPopular } from '@/routes';
import type { Paginated, SharedPageProps } from '@/types';

type Props = {
    skins: Paginated<SkinCardData>;
};

export default function SkinsPopular({ skins }: Props) {
    const { auth } = usePage<SharedPageProps>().props;

    return (
        <>
            <Head title="Public Skins" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-6 text-sm text-slate-500 dark:text-slate-400">Skins / Public / Most Popular</div>

                    <div className="mb-4 flex items-center">
                        <Link
                            href={skinsNewest.url()}
                            className="rounded-l-md border border-r-0 border-slate-300 bg-white px-4 py-2 text-base font-medium text-slate-800 hover:bg-green-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600"
                        >
                            Newest
                        </Link>
                        <Link
                            href={skinsPopular.url()}
                            className="rounded-r-md border border-slate-300 bg-green-50 px-4 py-2 text-base font-medium text-green-800 dark:border-green-700 dark:bg-green-800 dark:text-green-300"
                        >
                            Most Popular
                        </Link>
                    </div>

                    <div className="grid auto-rows-max grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {skins.data.length === 0 && <p className="text-black dark:text-white">None found.</p>}
                        {skins.data.map((skin) => (
                            <SkinCard key={skin.uuid} skin={skin} authenticated={Boolean(auth.user)} />
                        ))}
                    </div>

                    {skins.links.length > 3 && (
                        <div className="mt-4 flex flex-wrap justify-center gap-2">
                            {skins.links.map((link, index) =>
                                link.url ? (
                                    <Link
                                        key={`${link.label}-${index}`}
                                        href={link.url}
                                        className={`rounded px-3 py-1 text-sm ${link.active ? 'bg-green-600 text-white' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200'}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ) : (
                                    <span
                                        key={`${link.label}-${index}`}
                                        className="rounded px-3 py-1 text-sm text-slate-400"
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ),
                            )}
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}
