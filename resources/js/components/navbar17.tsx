import { Link, usePage } from '@inertiajs/react';
import {
    BookOpenIcon,
    CaretUpDownIcon,
    ChartBarIcon,
    FloppyDiskIcon,
    GameControllerIcon,
    HardDrivesIcon,
    HouseIcon,
    LayoutIcon,
    ListIcon,
    NewspaperIcon,
    PackageIcon,
    PencilSimpleIcon,
    ShieldIcon,
    SignOutIcon,
    SmileyIcon,
    StarIcon,
    TagIcon,
    UserIcon,
    UsersIcon,
    XIcon,
} from '@phosphor-icons/react';
import React, { useEffect, useMemo, useRef, useState } from 'react';

import { LanguageNav } from '@/components/language-nav';
import { NotificationsNav } from '@/components/notifications-nav';
import { ThemeToggle } from '@/components/theme-toggle';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuContent,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    NavigationMenuTrigger,
} from '@/components/ui/navigation-menu';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { useTranslations } from '@/hooks/use-translations';
import { cn } from '@/lib/utils';
import {
    dashboard,
    home,
    login,
    logout,
    register,
    review,
    skinHome,
    skinsPopular,
    wiki,
} from '@/routes';
import { index as apiTokensIndex } from '@/routes/api-tokens';
import { index as blogIndex } from '@/routes/blog';
import { index as memberIndex, show as memberShow } from '@/routes/member';
import { show as profileShow } from '@/routes/profile';
import { index as resourceIndex } from '@/routes/resource';
import { index as saveIndex } from '@/routes/save';
import { index as serverIndex } from '@/routes/server';
import type { SharedPageProps } from '@/types';

type NavIcon = React.ComponentType<{ className?: string; weight?: 'regular' | 'fill' }>;

type TranslateFn = (key: string, replace?: Record<string, string | number>) => string;

type NavLinkItem = {
    type: 'link';
    id: string;
    label: string;
    link: string;
    isActive: boolean;
    icon: NavIcon;
    separatorBefore?: boolean;
};

type NavGroupItem = {
    type: 'group';
    id: string;
    label: string;
    isActive: boolean;
    icon: NavIcon;
    children: NavLinkItem[];
};

type NavItem = NavLinkItem | NavGroupItem;

type NavbarVariant = 'dark' | 'light';

type UserMenuItem = {
    id: string;
    label: string;
    href: string;
    icon: React.ComponentType<{ className?: string }>;
    external?: boolean;
    method?: 'get' | 'post';
    separatorBefore?: boolean;
};

interface Navbar17Props {
    className?: string;
    variant?: NavbarVariant;
}

const navLinkClass =
    'bg-transparent p-0 text-sm font-medium hover:bg-transparent focus:bg-transparent focus-visible:ring-0 data-active:bg-transparent data-active:hover:bg-transparent data-active:focus:bg-transparent';

const navTriggerClass =
    'h-auto gap-1.5 bg-transparent p-0 text-sm font-medium hover:bg-transparent focus:bg-transparent focus-visible:ring-0 data-popup-open:bg-transparent data-open:bg-transparent data-popup-open:hover:bg-transparent data-open:hover:bg-transparent data-open:focus:bg-transparent';

function initials(name: string): string {
    return name
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0] ?? '')
        .join('')
        .slice(0, 2)
        .toUpperCase();
}

function navItemColour(isActive: boolean, isLight: boolean): string {
    if (isActive) {
        return isLight ? 'text-foreground' : 'text-white';
    }

    return isLight ? 'text-muted-foreground hover:text-foreground' : 'text-white/70 hover:text-white';
}

function isMySkinsPath(path: string): boolean {
    return (
        path === '/skin' ||
        path.startsWith('/skin/my') ||
        path.startsWith('/skin/upload') ||
        path.startsWith('/skin/import')
    );
}

function buildNavItems(path: string, isAuthenticated: boolean, t: TranslateFn): NavItem[] {
    const gameChildren: NavLinkItem[] = [
        {
            type: 'link',
            id: 'Skins',
            label: t('Skins'),
            link: skinsPopular.url(),
            icon: SmileyIcon,
            isActive: path.startsWith('/skin') && (! isAuthenticated || ! isMySkinsPath(path)),
        },
        {
            type: 'link',
            id: 'Resources',
            label: t('Resources'),
            link: resourceIndex.url(),
            icon: PackageIcon,
            isActive: path.startsWith('/resource'),
        },
        {
            type: 'link',
            id: 'Servers',
            label: t('Servers'),
            link: serverIndex.url(),
            icon: HardDrivesIcon,
            isActive: path.startsWith('/server'),
        },
    ];

    if (isAuthenticated) {
        gameChildren.push({
            type: 'link',
            id: 'My Skins',
            label: t('My Skins'),
            link: skinHome.url(),
            icon: SmileyIcon,
            isActive: isMySkinsPath(path),
            separatorBefore: true,
        });
        gameChildren.push({
            type: 'link',
            id: 'My Save',
            label: t('My Save'),
            link: saveIndex.url(),
            icon: FloppyDiskIcon,
            isActive: path.startsWith('/save'),
        });
    }

    return [
        { type: 'link', id: 'Home', label: t('Home'), link: home.url(), icon: HouseIcon, isActive: path === '/' },
        {
            type: 'link',
            id: 'Blog',
            label: t('Blog'),
            link: blogIndex.url(),
            icon: NewspaperIcon,
            isActive: path.startsWith('/blog'),
        },
        {
            type: 'group',
            id: 'Game',
            label: t('Game'),
            icon: GameControllerIcon,
            isActive:
                path.startsWith('/skin') ||
                path.startsWith('/resource') ||
                path.startsWith('/server') ||
                path.startsWith('/save'),
            children: gameChildren,
        },
        {
            type: 'link',
            id: 'Members',
            label: t('Members'),
            link: memberIndex.url(),
            icon: UsersIcon,
            isActive: path.startsWith('/member'),
        },
        {
            type: 'link',
            id: 'Review',
            label: t('Review'),
            link: review.url(),
            icon: StarIcon,
            isActive: path.startsWith('/review'),
        },
        { type: 'link', id: 'Wiki', label: t('Wiki'), link: wiki.url(), icon: BookOpenIcon, isActive: false },
    ];
}

const Navbar17 = ({ className, variant = 'dark' }: Navbar17Props) => {
    const page = usePage<SharedPageProps>();
    const { auth } = page.props;
    const { t } = useTranslations();
    const path = page.url.split('?')[0] ?? '';
    const isLight = variant === 'light';
    const isAuthenticated = Boolean(auth.user);

    const navItems = useMemo(() => buildNavItems(path, isAuthenticated, t), [path, isAuthenticated, t]);
    const activeItem = navItems.find((item) => item.isActive)?.id ?? '';

    const cta = auth.user
        ? { label: t('Dashboard'), href: dashboard.url() }
        : { label: t('Sign up'), href: register.url() };

    const userMenuItems = useMemo(() => {
        if (! auth.user) {
            return [] as UserMenuItem[];
        }

        const items: UserMenuItem[] = [
            {
                id: 'Dashboard',
                label: t('Dashboard'),
                href: dashboard.url(),
                icon: LayoutIcon,
            },
            {
                id: 'Profile',
                label: t('Profile'),
                href: memberShow.url(auth.user.username),
                icon: UserIcon,
                separatorBefore: true,
            },
            {
                id: 'Edit Profile',
                label: t('Edit Profile'),
                href: profileShow.url(),
                icon: PencilSimpleIcon,
            },
            {
                id: 'API Tokens',
                label: t('API Tokens'),
                href: apiTokensIndex.url(),
                icon: ShieldIcon,
            },
        ];

        if (auth.user.is_admin) {
            items.push(
                {
                    id: 'Tags',
                    label: t('Tags'),
                    href: '/mod/tags',
                    icon: TagIcon,
                    separatorBefore: true,
                },
                {
                    id: 'Analytics',
                    label: t('Analytics'),
                    href: '/mod/analytics',
                    icon: ChartBarIcon,
                },
                {
                    id: 'Admin',
                    label: t('Admin'),
                    href: '/filament',
                    icon: ShieldIcon,
                    external: true,
                },
            );
        }

        items.push({
            id: 'Log Out',
            label: t('Log Out'),
            href: logout.url(),
            icon: SignOutIcon,
            method: 'post',
            separatorBefore: true,
        });

        return items;
    }, [auth.user, t]);

    const indicatorRef = useRef<HTMLDivElement>(null);
    const menuRef = useRef<HTMLUListElement>(null);

    useEffect(() => {
        const updateIndicator = () => {
            const activeEl = document.querySelector(`[data-nav-item="${activeItem}"]`) as HTMLElement | null;

            if (activeEl && indicatorRef.current && menuRef.current) {
                const menuRect = menuRef.current.getBoundingClientRect();
                const itemRect = activeEl.getBoundingClientRect();

                indicatorRef.current.style.width = `${itemRect.width}px`;
                indicatorRef.current.style.left = `${itemRect.left - menuRect.left}px`;
            } else if (indicatorRef.current) {
                indicatorRef.current.style.width = '0px';
            }
        };

        updateIndicator();
        window.addEventListener('resize', updateIndicator);

        return () => window.removeEventListener('resize', updateIndicator);
    }, [activeItem]);

    return (
        <section
            className={cn(
                'py-4',
                isLight ? 'border-b bg-background text-foreground' : 'text-white',
                className,
            )}
        >
            <nav className="container mx-auto flex max-w-7xl items-center justify-between px-4">
                <Link href={home()} className="flex items-center gap-2">
                    <img src="/img/pokemon3d_logo_sm.png" className="h-10 w-auto lg:h-12" alt="Pokémon 3D" />
                </Link>

                <NavigationMenu className="hidden lg:block" viewport={false}>
                    <NavigationMenuList
                        ref={menuRef}
                        className="relative flex items-center gap-6 rounded-4xl px-8 py-3"
                    >
                        {navItems.map((item) => {
                            const Icon = item.icon;

                            if (item.type === 'group') {
                                return (
                                    <NavigationMenuItem key={item.id}>
                                        <NavigationMenuTrigger
                                            data-nav-item={item.id}
                                            className={cn(
                                                navTriggerClass,
                                                'relative inline-flex cursor-pointer items-center transition-colors',
                                                ! isLight && 'drop-shadow',
                                                navItemColour(item.isActive, isLight),
                                            )}
                                        >
                                            <Icon className="size-4 shrink-0" />
                                            {item.label}
                                        </NavigationMenuTrigger>
                                        <NavigationMenuContent>
                                            <ul className="flex min-w-48 flex-col gap-1 p-2">
                                                {item.children.map((child) => {
                                                    const ChildIcon = child.icon;

                                                    return (
                                                        <React.Fragment key={child.id}>
                                                            {child.separatorBefore ? (
                                                                <li
                                                                    className="my-1 border-t border-border"
                                                                    aria-hidden="true"
                                                                />
                                                            ) : null}
                                                            <li>
                                                                <NavigationMenuLink asChild>
                                                                    <Link
                                                                        href={child.link}
                                                                        className={cn(
                                                                            'flex items-center gap-2 rounded-none px-3 py-2 text-sm font-medium',
                                                                            child.isActive
                                                                                ? 'bg-muted text-foreground'
                                                                                : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                                                                        )}
                                                                    >
                                                                        <ChildIcon className="size-4 shrink-0" />
                                                                        {child.label}
                                                                    </Link>
                                                                </NavigationMenuLink>
                                                            </li>
                                                        </React.Fragment>
                                                    );
                                                })}
                                            </ul>
                                        </NavigationMenuContent>
                                    </NavigationMenuItem>
                                );
                            }

                            return (
                                <NavigationMenuItem key={item.id}>
                                    <NavigationMenuLink asChild className={navLinkClass}>
                                        <Link
                                            href={item.link}
                                            data-nav-item={item.id}
                                            className={cn(
                                                'relative inline-flex cursor-pointer items-center gap-1.5 transition-colors',
                                                ! isLight && 'drop-shadow',
                                                navItemColour(item.isActive, isLight),
                                            )}
                                        >
                                            <Icon className="size-4 shrink-0" />
                                            {item.label}
                                        </Link>
                                    </NavigationMenuLink>
                                </NavigationMenuItem>
                            );
                        })}
                        <div
                            ref={indicatorRef}
                            className="absolute bottom-2 flex h-1 items-center justify-center transition-all duration-300"
                        >
                            <div
                                className={cn(
                                    'h-0.5 w-full rounded-t-none transition-all duration-300',
                                    isLight ? 'bg-foreground' : 'bg-white',
                                )}
                            />
                        </div>
                    </NavigationMenuList>
                </NavigationMenu>

                <MobileNav
                    navItems={navItems}
                    cta={cta}
                    variant={variant}
                    user={auth.user}
                    userMenuItems={userMenuItems}
                />

                <div className="hidden items-center gap-2 lg:flex">
                    <LanguageNav overlay={! isLight} />
                    <ThemeToggle overlay={! isLight} />
                    {auth.user ? (
                        <>
                            <NotificationsNav variant={variant} />
                            <NavUser
                                user={{
                                    name: auth.user.name,
                                    email: auth.user.email,
                                    avatar: auth.user.profile_photo_url,
                                }}
                                menuItems={userMenuItems}
                                variant={variant}
                            />
                        </>
                    ) : (
                        <>
                            <Button
                                variant="ghost"
                                size="sm"
                                className={cn(
                                    'h-10 py-2.5 text-sm font-normal',
                                    ! isLight && 'text-white hover:bg-white/10 hover:text-white',
                                )}
                                asChild
                            >
                                <Link href={login.url()}>{t('Log in')}</Link>
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                className={cn(
                                    'h-10 py-2.5 text-sm font-normal',
                                    ! isLight &&
                                        'border-white/40 bg-white/10 text-white hover:bg-white/20 hover:text-white',
                                )}
                                asChild
                            >
                                <Link href={cta.href}>{cta.label}</Link>
                            </Button>
                        </>
                    )}
                </div>
            </nav>
        </section>
    );
};

export { Navbar17 };

const AnimatedHamburger = ({ isOpen, variant }: { isOpen: boolean; variant: NavbarVariant }) => {
    const iconClass = variant === 'light' ? 'text-foreground' : 'text-white';

    return (
        <div className="group relative size-full">
            <div className="absolute flex size-full items-center justify-center">
                <ListIcon
                    className={cn(
                        'absolute size-6 transition-all duration-300',
                        iconClass,
                        isOpen ? 'rotate-90 opacity-0' : 'rotate-0 opacity-100',
                    )}
                />
                <XIcon
                    className={cn(
                        'absolute size-6 transition-all duration-300',
                        iconClass,
                        isOpen ? 'rotate-0 opacity-100' : '-rotate-90 opacity-0',
                    )}
                />
            </div>
        </div>
    );
};

function NavUser({
    user,
    menuItems,
    variant,
}: {
    user: {
        name: string;
        email: string;
        avatar?: string;
    };
    menuItems: UserMenuItem[];
    variant: NavbarVariant;
}) {
    const isLight = variant === 'light';

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="ghost"
                    className={cn(
                        'gap-2 px-2',
                        ! isLight && 'text-white hover:bg-white/10 hover:text-white',
                    )}
                >
                    <Avatar className="size-8">
                        {user.avatar ? <AvatarImage src={user.avatar} alt={user.name} /> : null}
                        <AvatarFallback>{initials(user.name)}</AvatarFallback>
                    </Avatar>
                    <CaretUpDownIcon className="hidden md:block" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56">
                <DropdownMenuLabel className="font-normal">
                    <div className="flex flex-col gap-1">
                        <p className="text-sm font-medium">{user.name}</p>
                        <p className="text-xs text-muted-foreground">{user.email}</p>
                    </div>
                </DropdownMenuLabel>
                <DropdownMenuSeparator />
                {menuItems.map((item) => {
                    const Icon = item.icon;
                    const content = (
                        <>
                            <Icon />
                            {item.label}
                        </>
                    );

                    let menuItem: React.ReactNode;

                    if (item.external) {
                        menuItem = (
                            <DropdownMenuItem asChild>
                                <a href={item.href} className="flex items-center gap-2">
                                    {content}
                                </a>
                            </DropdownMenuItem>
                        );
                    } else if (item.method === 'post') {
                        menuItem = (
                            <DropdownMenuItem asChild>
                                <Link
                                    href={item.href}
                                    method="post"
                                    as="button"
                                    className="flex w-full items-center gap-2"
                                >
                                    {content}
                                </Link>
                            </DropdownMenuItem>
                        );
                    } else {
                        menuItem = (
                            <DropdownMenuItem asChild>
                                <Link href={item.href} className="flex items-center gap-2">
                                    {content}
                                </Link>
                            </DropdownMenuItem>
                        );
                    }

                    return (
                        <React.Fragment key={item.id}>
                            {item.separatorBefore ? <DropdownMenuSeparator /> : null}
                            {menuItem}
                        </React.Fragment>
                    );
                })}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}

const MobileNav = ({
    navItems,
    cta,
    variant,
    user,
    userMenuItems,
}: {
    navItems: NavItem[];
    cta: { label: string; href: string };
    variant: NavbarVariant;
    user: SharedPageProps['auth']['user'];
    userMenuItems: UserMenuItem[];
}) => {
    const [isOpen, setIsOpen] = useState(false);
    const { t } = useTranslations();

    return (
        <div className="flex h-full items-center gap-1 lg:hidden">
            <LanguageNav overlay={variant !== 'light'} />
            <ThemeToggle overlay={variant !== 'light'} />
            {user ? <NotificationsNav variant={variant} /> : null}
            <Popover open={isOpen} onOpenChange={setIsOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant="ghost"
                        size="icon"
                        className={cn(
                            variant !== 'light' && 'text-white hover:bg-white/10 hover:text-white',
                        )}
                    >
                        <AnimatedHamburger isOpen={isOpen} variant={variant} />
                    </Button>
                </PopoverTrigger>

                <PopoverContent
                    align="end"
                    className="relative top-4 -right-4 block w-[calc(100vw-32px)] overflow-hidden rounded-xl p-0 sm:top-auto sm:right-auto sm:w-80 lg:hidden"
                >
                    <ul className="w-full bg-background py-4 text-foreground">
                        {navItems.map((navItem) => {
                            const Icon = navItem.icon;

                            if (navItem.type === 'group') {
                                return (
                                    <li key={navItem.id}>
                                        <div
                                            className={cn(
                                                'flex items-center gap-2 border-l-[3px] px-6 py-3 text-xs font-semibold uppercase tracking-wide',
                                                navItem.isActive
                                                    ? 'border-foreground text-foreground'
                                                    : 'border-transparent text-muted-foreground',
                                            )}
                                        >
                                            <Icon className="size-4 shrink-0" />
                                            {navItem.label}
                                        </div>
                                        <ul>
                                            {navItem.children.map((child) => {
                                                const ChildIcon = child.icon;

                                                return (
                                                    <React.Fragment key={child.id}>
                                                        {child.separatorBefore ? (
                                                            <li
                                                                className="mx-12 my-2 border-t border-border"
                                                                aria-hidden="true"
                                                            />
                                                        ) : null}
                                                        <li>
                                                            <Link
                                                                href={child.link}
                                                                onClick={() => setIsOpen(false)}
                                                                className={cn(
                                                                    'flex items-center gap-2 border-l-[3px] py-3 pr-6 pl-12 text-sm font-medium transition-all duration-75',
                                                                    child.isActive
                                                                        ? 'border-foreground text-foreground'
                                                                        : 'border-transparent text-muted-foreground hover:text-foreground',
                                                                )}
                                                            >
                                                                <ChildIcon className="size-4 shrink-0" />
                                                                {child.label}
                                                            </Link>
                                                        </li>
                                                    </React.Fragment>
                                                );
                                            })}
                                        </ul>
                                    </li>
                                );
                            }

                            return (
                                <li key={navItem.id}>
                                    <Link
                                        href={navItem.link}
                                        onClick={() => setIsOpen(false)}
                                        className={cn(
                                            'flex items-center gap-2 border-l-[3px] px-6 py-4 text-sm font-medium transition-all duration-75',
                                            navItem.isActive
                                                ? 'border-foreground text-foreground'
                                                : 'border-transparent text-muted-foreground hover:text-foreground',
                                        )}
                                    >
                                        <Icon className="size-4 shrink-0" />
                                        {navItem.label}
                                    </Link>
                                </li>
                            );
                        })}
                        {user ? (
                            <>
                                <li className="mx-6 my-2 border-t border-border" aria-hidden="true" />
                                {userMenuItems.map((item) => {
                                    const Icon = item.icon;

                                    return (
                                        <React.Fragment key={item.id}>
                                            {item.separatorBefore ? (
                                                <li className="mx-6 my-2 border-t border-border" aria-hidden="true" />
                                            ) : null}
                                            <li>
                                                {item.external ? (
                                                    <a
                                                        href={item.href}
                                                        onClick={() => setIsOpen(false)}
                                                        className="flex items-center gap-2 border-l-[3px] border-transparent px-6 py-4 text-sm font-medium text-muted-foreground hover:text-foreground"
                                                    >
                                                        <Icon className="size-4" />
                                                        {item.label}
                                                    </a>
                                                ) : (
                                                    <Link
                                                        href={item.href}
                                                        method={item.method}
                                                        as={item.method === 'post' ? 'button' : undefined}
                                                        onClick={() => setIsOpen(false)}
                                                        className="flex w-full items-center gap-2 border-l-[3px] border-transparent px-6 py-4 text-sm font-medium text-muted-foreground hover:text-foreground"
                                                    >
                                                        <Icon className="size-4" />
                                                        {item.label}
                                                    </Link>
                                                )}
                                            </li>
                                        </React.Fragment>
                                    );
                                })}
                            </>
                        ) : (
                            <li className="flex flex-col gap-2 px-7 py-2">
                                <Button variant="ghost" asChild>
                                    <Link href={login.url()} onClick={() => setIsOpen(false)}>
                                        {t('Log in')}
                                    </Link>
                                </Button>
                                <Button variant="outline" asChild>
                                    <Link href={cta.href} onClick={() => setIsOpen(false)}>
                                        {cta.label}
                                    </Link>
                                </Button>
                            </li>
                        )}
                    </ul>
                </PopoverContent>
            </Popover>
        </div>
    );
};
