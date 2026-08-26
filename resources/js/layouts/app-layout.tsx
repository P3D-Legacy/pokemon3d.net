import type { PropsWithChildren } from 'react';

import FlashBanner from '@/components/flash-banner';
import { Footer59 } from '@/components/footer59';
import { Navbar17 } from '@/components/navbar17';
import { TermsAcceptanceDialog } from '@/components/terms-acceptance-dialog';

export default function AppLayout({ children, title }: PropsWithChildren<{ title?: string }>) {
    return (
        <div className="flex min-h-svh flex-col bg-background">
            <div className="sticky top-0 z-50">
                <Navbar17 variant="light" />
            </div>

            <FlashBanner />
            <TermsAcceptanceDialog />

            {title ? (
                <div className="border-b bg-background">
                    <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <h1 className="text-xl font-semibold leading-tight text-foreground">{title}</h1>
                    </div>
                </div>
            ) : null}

            <main className="flex flex-1 flex-col">{children}</main>
            <Footer59 />
        </div>
    );
}
