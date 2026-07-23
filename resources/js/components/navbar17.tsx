import { Link, usePage } from '@inertiajs/react';
import {
    BookOpenIcon,
    CaretUpDownIcon,
    ChartBarIcon,
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
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
} from '@/components/ui/navigation-menu';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
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
import { index as serverIndex } from '@/routes/server';
import type { SharedPageProps } from '@/types';

type NavIcon = React.ComponentType<{ className?: string; weight?: 'regular' | 'fill' }>;

type NavItem = {
    name: string;
    link: string;
    isActive: boolean;
    icon: NavIcon;
};

type NavbarVariant = 'dark' | 'light';

type UserMenuItem = {
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

function initials(name: string): string {
    return name
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0] ?? '')
        .join('')
        .slice(0, 2)
        .toUpperCase();
}

function buildNavItems(path: string): NavItem[] {
    return [
        { name: 'Home', link: home.url(), icon: HouseIcon, isActive: path === '/' },
        { name: 'Blog', link: blogIndex.url(), icon: NewspaperIcon, isActive: path.startsWith('/blog') },
        { name: 'Skins', link: skinsPopular.url(), icon: SmileyIcon, isActive: path.startsWith('/skin') },
        { name: 'Resources', link: resourceIndex.url(), icon: PackageIcon, isActive: path.startsWith('/resource') },
        { name: 'Servers', link: serverIndex.url(), icon: HardDrivesIcon, isActive: path.startsWith('/server') },
        { name: 'Members', link: memberIndex.url(), icon: UsersIcon, isActive: path.startsWith('/member') },
        { name: 'Review', link: review.url(), icon: StarIcon, isActive: path.startsWith('/review') },
        { name: 'Wiki', link: wiki.url(), icon: BookOpenIcon, isActive: false },
    ];
}

const Navbar17 = ({ className, variant = 'dark' }: Navbar17Props) => {
    const page = usePage<SharedPageProps>();
    const { auth } = page.props;
    const path = page.url.split('?')[0] ?? '';
    const isLight = variant === 'light';

    const navItems = useMemo(() => buildNavItems(path), [path]);
    const activeItem = navItems.find((item) => item.isActive)?.name ?? '';

    const cta = auth.user
        ? { label: 'Dashboard', href: dashboard.url() }
        : { label: 'Sign up', href: register.url() };

    const userMenuItems = useMemo(() => {
        if (! auth.user) {
            return [] as UserMenuItem[];
        }

        const items: UserMenuItem[] = [
            {
                label: 'Dashboard',
                href: dashboard.url(),
                icon: LayoutIcon,
            },
            {
                label: 'My Skins',
                href: skinHome.url(),
                icon: SmileyIcon,
            },
            {
                label: 'Profile',
                href: memberShow.url(auth.user.username),
                icon: UserIcon,
                separatorBefore: true,
            },
            {
                label: 'Edit profile',
                href: profileShow.url(),
                icon: PencilSimpleIcon,
            },
            {
                label: 'API Tokens',
                href: apiTokensIndex.url(),
                icon: ShieldIcon,
            },
        ];

        if (auth.user.is_admin) {
            items.push(
                {
                    label: 'Tags',
                    href: '/mod/tags',
                    icon: TagIcon,
                    separatorBefore: true,
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

        items.push({
            label: 'Log out',
            href: logout.url(),
            icon: SignOutIcon,
            method: 'post',
            separatorBefore: true,
        });

        return items;
    }, [auth.user]);

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

                <NavigationMenu className="hidden lg:block">
                    <NavigationMenuList
                        ref={menuRef}
                        className="relative flex items-center gap-6 rounded-4xl px-8 py-3"
                    >
                        {navItems.map((item) => {
                            const Icon = item.icon;

                            return (
                                <React.Fragment key={item.name}>
                                    <NavigationMenuItem>
                                        <NavigationMenuLink asChild className={navLinkClass}>
                                            <Link
                                                href={item.link}
                                                data-nav-item={item.name}
                                                className={cn(
                                                    'relative inline-flex items-center gap-1.5 cursor-pointer transition-colors',
                                                    ! isLight && 'drop-shadow',
                                                    item.isActive
                                                        ? isLight
                                                            ? 'text-foreground'
                                                            : 'text-white'
                                                        : isLight
                                                          ? 'text-muted-foreground hover:text-foreground'
                                                          : 'text-white/70 hover:text-white',
                                                )}
                                            >
                                                <Icon className="size-4 shrink-0" />
                                                {item.name}
                                            </Link>
                                        </NavigationMenuLink>
                                    </NavigationMenuItem>
                                </React.Fragment>
                            );
                        })}
                        <div
                            ref={indicatorRef}
                            className="absolute bottom-2 flex h-1 items-center justify-center px-2 transition-all duration-300"
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
                    activeItem={activeItem}
                    navItems={navItems}
                    cta={cta}
                    variant={variant}
                    user={auth.user}
                    userMenuItems={userMenuItems}
                />

                <div className="hidden items-center gap-2 lg:flex">
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
                                <Link href={login.url()}>Log in</Link>
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
                    <span className="hidden text-sm font-medium md:inline">{user.name}</span>
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
                        <React.Fragment key={item.label}>
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
    activeItem,
    navItems,
    cta,
    variant,
    user,
    userMenuItems,
}: {
    activeItem: string;
    navItems: NavItem[];
    cta: { label: string; href: string };
    variant: NavbarVariant;
    user: SharedPageProps['auth']['user'];
    userMenuItems: UserMenuItem[];
}) => {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <div className="flex h-full items-center gap-1 lg:hidden">
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

                            return (
                                <li key={navItem.name}>
                                    <Link
                                        href={navItem.link}
                                        onClick={() => setIsOpen(false)}
                                        className={cn(
                                            'flex items-center gap-2 border-l-[3px] px-6 py-4 text-sm font-medium transition-all duration-75',
                                            activeItem === navItem.name
                                                ? 'border-foreground text-foreground'
                                                : 'border-transparent text-muted-foreground hover:text-foreground',
                                        )}
                                    >
                                        <Icon className="size-4 shrink-0" />
                                        {navItem.name}
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
                                        <React.Fragment key={item.label}>
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
                                        Log in
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
