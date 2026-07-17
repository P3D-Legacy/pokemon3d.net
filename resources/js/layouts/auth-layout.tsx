import { Link } from '@inertiajs/react';
import type { PropsWithChildren } from 'react';

import { home } from '@/routes';

export default function AuthLayout({ children }: PropsWithChildren) {
    return (
        <div className="flex min-h-screen flex-col items-center bg-slate-100 pt-6 sm:justify-center sm:pt-0 dark:bg-slate-900">
            <Link href={home()} className="mb-4">
                <img src="/img/pokemon3d_logo.png" alt="Pokémon 3D" className="h-16 w-auto" />
            </Link>

            <div className="w-full overflow-hidden bg-white px-6 py-6 shadow-md sm:max-w-md sm:rounded-lg dark:bg-slate-800">
                {children}
            </div>
        </div>
    );
}
