import { Link, usePage } from '@inertiajs/react';
import {
    BooksIcon,
    CaretDownIcon,
    HardDrivesIcon,
    LayoutIcon,
    NewspaperIcon,
    SignOutIcon,
    SmileyIcon,
    StarIcon,
    UsersIcon,
} from '@phosphor-icons/react';
import type { PropsWithChildren, ReactNode } from 'react';
import { useState } from 'react';

import { dashboard, home, login, logout, review, skinHome, skinsNewest, skinsPopular } from '@/routes';
import { index as apiTokensIndex } from '@/routes/api-tokens';
import { index as blogIndex } from '@/routes/blog';
import { index as memberIndex } from '@/routes/member';
import { index as notificationsIndex } from '@/routes/notifications';
import { show as profileShow } from '@/routes/profile';
import { index as resourceIndex } from '@/routes/resource';
import { index as serverIndex } from '@/routes/server';
import type { SharedPageProps } from '@/types';

function NavLink({
    href,
    active,
    children,
}: {
    href: string;
    active?: boolean;
    children: ReactNode;
}) {
    return (
        <Link
            href={href}
            className={`inline-flex items-center gap-1 border-b-2 px-1 pt-1 text-sm font-medium ${
                active
                    ? 'border-green-600 text-slate-900 dark:text-slate-100'
                    : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-300'
            }`}
        >
            {children}
        </Link>
    );
}

export default function AppLayout({ children, title }: PropsWithChildren<{ title?: string }>) {
    const page = usePage<SharedPageProps>();
    const { auth, appName } = page.props;
    const [mobileOpen, setMobileOpen] = useState(false);
    const [skinsOpen, setSkinsOpen] = useState(false);
    const [userOpen, setUserOpen] = useState(false);
    const path = page.url.split('?')[0] ?? '';

    return (
        <div className="min-h-screen bg-slate-100 dark:bg-slate-800">
            <nav className="border-b border-slate-100 bg-white dark:border-slate-800 dark:bg-black">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 justify-between">
                        <div className="flex items-center gap-8">
                            <Link href={home()} className="flex items-center">
                                <img src="/img/pokemon3d_logo_sm.png" alt={appName} className="h-8 w-auto" />
                            </Link>

                            <div className="hidden items-center gap-4 lg:flex">
                                <NavLink href={dashboard.url()} active={path.startsWith('/dashboard')}>
                                    <LayoutIcon className="size-4" />
                                    Dashboard
                                </NavLink>
                                <NavLink href={blogIndex.url()} active={path.startsWith('/blog')}>
                                    <NewspaperIcon className="size-4" />
                                    Blog
                                </NavLink>
                                <div className="relative">
                                    <button
                                        type="button"
                                        className="inline-flex items-center gap-1 border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-300"
                                        onClick={() => setSkinsOpen((value) => ! value)}
                                    >
                                        <SmileyIcon className="size-4" />
                                        Skins
                                        <CaretDownIcon className="size-3" />
                                    </button>
                                    {skinsOpen && (
                                        <div className="absolute left-0 z-20 mt-2 w-48 rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 dark:bg-slate-900">
                                            <Link href={skinHome.url()} className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                My Skins
                                            </Link>
                                            <Link href={skinsPopular.url()} className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Most Popular
                                            </Link>
                                            <Link href={skinsNewest.url()} className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Newest
                                            </Link>
                                        </div>
                                    )}
                                </div>
                                <NavLink href={resourceIndex.url()} active={path.startsWith('/resource')}>
                                    <BooksIcon className="size-4" />
                                    Resources
                                </NavLink>
                                <NavLink href={serverIndex.url()} active={path.startsWith('/server')}>
                                    <HardDrivesIcon className="size-4" />
                                    Servers
                                </NavLink>
                                <NavLink href={memberIndex.url()} active={path.startsWith('/member')}>
                                    <UsersIcon className="size-4" />
                                    Members
                                </NavLink>
                                <NavLink href={review.url()} active={path.startsWith('/review')}>
                                    <StarIcon className="size-4" />
                                    Review
                                </NavLink>
                            </div>
                        </div>

                        <div className="flex items-center gap-3">
                            <button
                                type="button"
                                className="rounded border border-slate-300 px-2 py-1 text-sm lg:hidden dark:border-slate-600"
                                onClick={() => setMobileOpen((value) => ! value)}
                            >
                                Menu
                            </button>

                            {auth.user ? (
                                <div className="relative">
                                    <button
                                        type="button"
                                        className="flex items-center gap-2 rounded-full"
                                        onClick={() => setUserOpen((value) => ! value)}
                                    >
                                        <img
                                            src={auth.user.profile_photo_url}
                                            alt={auth.user.name}
                                            className="size-8 rounded-full object-cover"
                                        />
                                        {(auth.user.unread_notifications_count ?? 0) > 0 && (
                                            <span className="rounded-full bg-green-600 px-2 py-0.5 text-xs text-white">
                                                {auth.user.unread_notifications_count}
                                            </span>
                                        )}
                                    </button>
                                    {userOpen && (
                                        <div className="absolute right-0 z-20 mt-2 w-56 rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 dark:bg-slate-900">
                                            <div className="border-b border-slate-100 px-4 py-2 text-xs text-slate-400 dark:border-slate-700">
                                                {auth.user.name}
                                            </div>
                                            <Link href={profileShow.url()} className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Profile
                                            </Link>
                                            <Link href={apiTokensIndex.url()} className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                API Tokens
                                            </Link>
                                            <Link href={notificationsIndex.url()} className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Notifications
                                            </Link>
                                            {auth.user.is_admin && (
                                                <>
                                                    <Link href="/mod/tags" className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                        Tags
                                                    </Link>
                                                    <Link href="/mod/analytics" className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                        Analytics
                                                    </Link>
                                                    <a href="/filament" className="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                                        Admin
                                                    </a>
                                                </>
                                            )}
                                            <Link
                                                href={logout()}
                                                method="post"
                                                as="button"
                                                className="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800"
                                            >
                                                <SignOutIcon className="size-4" />
                                                Log out
                                            </Link>
                                        </div>
                                    )}
                                </div>
                            ) : (
                                <Link href={login.url()} className="text-sm font-medium text-green-700 dark:text-green-400">
                                    Log in
                                </Link>
                            )}
                        </div>
                    </div>
                </div>

                {mobileOpen && (
                    <div className="space-y-1 border-t border-slate-100 px-4 py-3 lg:hidden dark:border-slate-800">
                        <Link href={dashboard.url()} className="block py-2 text-sm">Dashboard</Link>
                        <Link href={blogIndex.url()} className="block py-2 text-sm">Blog</Link>
                        <Link href={skinHome.url()} className="block py-2 text-sm">My Skins</Link>
                        <Link href={resourceIndex.url()} className="block py-2 text-sm">Resources</Link>
                        <Link href={serverIndex.url()} className="block py-2 text-sm">Servers</Link>
                        <Link href={memberIndex.url()} className="block py-2 text-sm">Members</Link>
                        <Link href={review.url()} className="block py-2 text-sm">Review</Link>
                    </div>
                )}
            </nav>

            {title ? (
                <header className="bg-white shadow dark:bg-black">
                    <div className="mx-auto max-w-7xl px-4 py-6 text-slate-800 sm:px-6 lg:px-8 dark:text-slate-200">
                        <h1 className="text-xl font-semibold leading-tight">{title}</h1>
                    </div>
                </header>
            ) : null}

            <main>{children}</main>
        </div>
    );
}
