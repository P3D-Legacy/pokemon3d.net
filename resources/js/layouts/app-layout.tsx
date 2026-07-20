import { usePage } from '@inertiajs/react';
import {
    BellIcon,
    BooksIcon,
    ChartBarIcon,
    HardDrivesIcon,
    LayoutIcon,
    NewspaperIcon,
    ShieldIcon,
    SignOutIcon,
    SmileyIcon,
    StarIcon,
    TagIcon,
    UserIcon,
    UsersIcon,
} from '@phosphor-icons/react';
import type { PropsWithChildren } from 'react';

import {
    ApplicationShell3,
    type ShellNavGroup,
    type ShellUserMenuItem,
} from '@/components/application-shell3';
import { dashboard, home, login, logout, review, skinHome, skinsNewest, skinsPopular } from '@/routes';
import { index as apiTokensIndex } from '@/routes/api-tokens';
import { index as blogIndex } from '@/routes/blog';
import { index as memberIndex } from '@/routes/member';
import { index as notificationsIndex } from '@/routes/notifications';
import { show as profileShow } from '@/routes/profile';
import { index as resourceIndex } from '@/routes/resource';
import { index as serverIndex } from '@/routes/server';
import type { SharedPageProps } from '@/types';

export default function AppLayout({ children, title }: PropsWithChildren<{ title?: string }>) {
    const page = usePage<SharedPageProps>();
    const { auth, appName } = page.props;
    const path = page.url.split('?')[0] ?? '';

    const navGroups: ShellNavGroup[] = [
        {
            title: 'Main',
            items: [
                {
                    label: 'Dashboard',
                    href: dashboard.url(),
                    icon: LayoutIcon,
                    isActive: path.startsWith('/dashboard'),
                },
                {
                    label: 'Blog',
                    href: blogIndex.url(),
                    icon: NewspaperIcon,
                    isActive: path.startsWith('/blog'),
                },
                {
                    label: 'Review',
                    href: review.url(),
                    icon: StarIcon,
                    isActive: path.startsWith('/review'),
                },
            ],
        },
        {
            title: 'Skins',
            items: [
                {
                    label: 'My Skins',
                    href: skinHome.url(),
                    icon: SmileyIcon,
                    isActive: path === '/skin' || (path.startsWith('/skin/') && ! path.startsWith('/skin/public')),
                },
                {
                    label: 'Most Popular',
                    href: skinsPopular.url(),
                    icon: SmileyIcon,
                    isActive: path.startsWith('/skin/public/popular'),
                },
                {
                    label: 'Newest',
                    href: skinsNewest.url(),
                    icon: SmileyIcon,
                    isActive: path.startsWith('/skin/public/new'),
                },
            ],
        },
        {
            title: 'Explore',
            items: [
                {
                    label: 'Resources',
                    href: resourceIndex.url(),
                    icon: BooksIcon,
                    isActive: path.startsWith('/resource'),
                },
                {
                    label: 'Servers',
                    href: serverIndex.url(),
                    icon: HardDrivesIcon,
                    isActive: path.startsWith('/server'),
                },
                {
                    label: 'Members',
                    href: memberIndex.url(),
                    icon: UsersIcon,
                    isActive: path.startsWith('/member'),
                },
            ],
        },
    ];

    const userMenuItems: ShellUserMenuItem[] = [];

    if (auth.user) {
        userMenuItems.push(
            {
                label: 'Profile',
                href: profileShow.url(),
                icon: UserIcon,
            },
            {
                label: 'API Tokens',
                href: apiTokensIndex.url(),
                icon: ShieldIcon,
            },
            {
                label: 'Notifications',
                href: notificationsIndex.url(),
                icon: BellIcon,
            },
        );

        if (auth.user.is_admin) {
            userMenuItems.push(
                {
                    label: 'Tags',
                    href: '/mod/tags',
                    icon: TagIcon,
                },
                {
                    label: 'Analytics',
                    href: '/mod/analytics',
                    icon: ChartBarIcon,
                },
                {
                    label: 'Admin',
                    href: '/filament',
                    icon: ShieldIcon,
                    external: true,
                },
            );
        }

        userMenuItems.push({
            label: 'Log out',
            href: logout.url(),
            icon: SignOutIcon,
            method: 'post',
        });
    }

    return (
        <ApplicationShell3
            title={title}
            logo={{
                src: '/img/pokemon3d_logo_sm.png',
                alt: appName,
                title: appName,
                href: home.url(),
            }}
            navGroups={navGroups}
            user={
                auth.user
                    ? {
                          name: auth.user.name,
                          email: auth.user.email,
                          avatar: auth.user.profile_photo_url,
                          unreadNotificationsCount: auth.user.unread_notifications_count,
                      }
                    : null
            }
            userMenuItems={userMenuItems}
            loginHref={login.url()}
        >
            {children}
        </ApplicationShell3>
    );
}
