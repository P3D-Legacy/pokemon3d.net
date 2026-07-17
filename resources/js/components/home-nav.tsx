import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';

import { dashboard, discord, github, home, login, wiki } from '@/routes';
import { index as blogIndex } from '@/routes/blog';
import { index as resourceIndex } from '@/routes/resource';
import { index as serverIndex } from '@/routes/server';
import type { SharedPageProps } from '@/types';

export default function HomeNav() {
    const { auth } = usePage<SharedPageProps>().props;
    const [open, setOpen] = useState(false);

    const items = [
        { label: 'Blog', href: blogIndex.url() },
        { label: 'Resources', href: resourceIndex.url() },
        { label: 'Servers', href: serverIndex.url() },
        { label: 'Wiki', href: wiki.url() },
        { label: 'GitHub', href: github.url() },
        { label: 'Discord', href: discord.url() },
        auth.user
            ? { label: 'Go to Dashboard', href: dashboard.url() }
            : { label: 'Log in', href: login.url() },
    ];

    return (
        <nav className="top-0 z-30 w-full py-1 text-white lg:py-6">
            <div className="container mx-auto mt-0 flex w-full flex-wrap items-center justify-between px-2 py-2 lg:py-6">
                <Link href={home()} className="pl-4 font-mono text-2xl font-bold text-white no-underline lg:text-4xl">
                    <img src="/img/pokemon3d_logo_sm.png" alt="Pokémon 3D" className="h-10 w-auto lg:h-14" />
                </Link>

                <button
                    type="button"
                    aria-label="Menu"
                    className="mr-4 flex items-center rounded border border-white px-2 py-1 text-white lg:hidden"
                    onClick={() => setOpen((value) => ! value)}
                >
                    <span className="text-sm font-semibold">{open ? 'Close' : 'Menu'}</span>
                </button>

                <ul
                    className={`${open ? 'flex' : 'hidden'} w-full flex-col gap-2 pt-2 lg:flex lg:w-auto lg:flex-row lg:items-center lg:gap-4 lg:pt-0`}
                >
                    {items.map((item) => (
                        <li key={item.label}>
                            <Link
                                href={item.href}
                                className="block rounded px-3 py-2 text-sm font-semibold text-white hover:bg-white/10"
                            >
                                {item.label}
                            </Link>
                        </li>
                    ))}
                </ul>
            </div>
        </nav>
    );
}
