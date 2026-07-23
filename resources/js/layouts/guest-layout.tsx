import { usePage } from '@inertiajs/react';
import type { PropsWithChildren, ReactNode } from 'react';

import { Footer59 } from '@/components/footer59';
import type { SharedPageProps } from '@/types';

function EnvBanner() {
    const { env } = usePage<SharedPageProps>().props;

    if (env === 'production') {
        return null;
    }

    return (
        <div className="pointer-events-none fixed inset-x-0 top-0 z-50">
            <div className="mx-auto max-w-xs p-0">
                <div className="rounded-b-lg bg-yellow-600/80 p-0 shadow">
                    <p className="w-full truncate text-center text-sm font-bold text-white">
                        {env === 'staging' ? 'QA: FOR TESTING ONLY' : 'DEV MODE'}
                    </p>
                </div>
            </div>
        </div>
    );
}

export default function GuestLayout({ children }: PropsWithChildren) {
    return (
        <div className="flex min-h-screen flex-col bg-spring bg-top bg-repeat font-sans leading-relaxed tracking-wide">
            <EnvBanner />
            <main className="flex-1">{children as ReactNode}</main>
            <Footer59 />
        </div>
    );
}
