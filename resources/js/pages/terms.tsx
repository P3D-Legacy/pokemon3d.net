import { Head, Link } from '@inertiajs/react';

import { home } from '@/routes';

type Props = {
    html: string;
};

export default function Terms({ html }: Props) {
    return (
        <>
            <Head title="Terms of Service" />

            <div className="bg-slate-100 pt-4">
                <div className="flex min-h-screen flex-col items-center pt-6 sm:pt-0">
                    <Link href={home()}>
                        <img src="/img/pokemon3d_logo.png" alt="Pokémon 3D" className="h-16 w-auto" />
                    </Link>
                    <div
                        className="prose mt-6 mb-10 w-full overflow-hidden bg-white p-6 shadow-md sm:max-w-2xl sm:rounded-lg"
                        dangerouslySetInnerHTML={{ __html: html }}
                    />
                </div>
            </div>
        </>
    );
}
