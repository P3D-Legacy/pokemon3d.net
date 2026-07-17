import { Head } from '@inertiajs/react';

import SkinCard, { type SkinCardData } from '@/components/skin-card';

type Props = {
    skins: SkinCardData[];
};

export default function SkinsUploaded({ skins }: Props) {
    return (
        <>
            <Head title="Uploaded Skins" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h2 className="mb-4 border-b-2 border-slate-200 pb-1 text-3xl font-extrabold leading-9 text-slate-800 dark:border-slate-700 dark:text-slate-50">
                        Uploaded Skins
                    </h2>

                    <div className="grid auto-rows-max grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {skins.length === 0 && <p className="dark:text-white">None found.</p>}
                        {skins.map((skin) => (
                            <SkinCard key={skin.uuid} skin={skin} mode="admin" />
                        ))}
                    </div>
                </div>
            </div>
        </>
    );
}
