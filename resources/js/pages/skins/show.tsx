import { Head, usePage } from '@inertiajs/react';

import SkinCard, { type SkinCardData } from '@/components/skin-card';
import type { SharedPageProps } from '@/types';

type Props = {
    skin: SkinCardData;
};

export default function SkinsShow({ skin }: Props) {
    const { auth } = usePage<SharedPageProps>().props;

    return (
        <>
            <Head title={`Public Skins: ${skin.name}`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="mb-6 text-sm text-slate-500 dark:text-slate-400">Skins / Public / {skin.name}</div>
                    <SkinCard skin={skin} mode="detail" authenticated={Boolean(auth.user)} />
                </div>
            </div>
        </>
    );
}
