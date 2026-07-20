import { Link } from '@inertiajs/react';
import { CaretDownIcon, CaretUpDownIcon, ListIcon, SignOutIcon } from '@phosphor-icons/react';
import type { ComponentType, PropsWithChildren, ReactNode, SVGProps } from 'react';
import { Fragment, useState } from 'react';

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
import { ScrollArea } from '@/components/ui/scroll-area';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { cn } from '@/lib/utils';

export type ShellNavItem = {
    label: string;
    href: string;
    icon?: ComponentType<SVGProps<SVGSVGElement>>;
    isActive?: boolean;
    children?: ShellNavItem[];
};

export type ShellNavGroup = {
    title: string;
    items: ShellNavItem[];
};

export type ShellUser = {
    name: string;
    email: string;
    avatar?: string;
    unreadNotificationsCount?: number;
};

export type ShellUserMenuItem = {
    label: string;
    href: string;
    icon?: ComponentType<SVGProps<SVGSVGElement>>;
    external?: boolean;
    method?: 'get' | 'post';
};

type ApplicationShell3Props = PropsWithChildren<{
    className?: string;
    title?: string;
    logo: {
        src: string;
        alt: string;
        title: string;
        href: string;
    };
    navGroups: ShellNavGroup[];
    user?: ShellUser | null;
    userMenuItems?: ShellUserMenuItem[];
    loginHref?: string;
}>;

function initials(name: string): string {
    return name
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0] ?? '')
        .join('')
        .slice(0, 2)
        .toUpperCase();
}

function NavDropdown({ group }: { group: ShellNavGroup }) {
    const groupActive = group.items.some(
        (item) => item.isActive || item.children?.some((child) => child.isActive),
    );

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="ghost"
                    className={cn('gap-1', groupActive && 'bg-muted font-medium')}
                >
                    {group.title}
                    <CaretDownIcon data-icon="inline-end" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="start" className="w-48">
                {group.items.map((item) => {
                    const Icon = item.icon;

                    return (
                        <Fragment key={item.label}>
                            <DropdownMenuItem asChild>
                                <Link href={item.href} className="flex items-center gap-2">
                                    {Icon ? <Icon /> : null}
                                    {item.label}
                                </Link>
                            </DropdownMenuItem>
                            {item.children?.map((child) => {
                                const ChildIcon = child.icon;

                                return (
                                    <DropdownMenuItem key={child.label} asChild className="pl-6">
                                        <Link href={child.href} className="flex items-center gap-2">
                                            {ChildIcon ? <ChildIcon /> : null}
                                            {child.label}
                                        </Link>
                                    </DropdownMenuItem>
                                );
                            })}
                        </Fragment>
                    );
                })}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}

function MobileNav({
    logo,
    navGroups,
}: {
    logo: ApplicationShell3Props['logo'];
    navGroups: ShellNavGroup[];
}) {
    const [open, setOpen] = useState(false);

    return (
        <Sheet open={open} onOpenChange={setOpen}>
            <SheetTrigger asChild>
                <Button variant="ghost" size="icon" className="md:hidden">
                    <ListIcon />
                    <span className="sr-only">Open menu</span>
                </Button>
            </SheetTrigger>
            <SheetContent side="left" className="w-72 p-0">
                <SheetHeader className="px-4 pt-4">
                    <SheetTitle className="flex items-center gap-2">
                        <img src={logo.src} alt={logo.alt} className="h-8 w-auto" />
                        <span className="font-semibold">{logo.title}</span>
                    </SheetTitle>
                </SheetHeader>
                <ScrollArea className="min-h-0 flex-1">
                    <nav className="flex flex-col gap-4 px-4 py-4">
                        {navGroups.map((group) => (
                            <div key={group.title}>
                                <div className="mb-2 text-xs font-medium tracking-wider text-muted-foreground uppercase">
                                    {group.title}
                                </div>
                                <div className="flex flex-col gap-1">
                                    {group.items.map((item) => {
                                        const Icon = item.icon;

                                        return (
                                            <Fragment key={item.label}>
                                                <Link
                                                    href={item.href}
                                                    onClick={() => setOpen(false)}
                                                    className={cn(
                                                        'flex items-center gap-2 rounded-md px-2 py-1.5 text-sm hover:bg-muted',
                                                        item.isActive && 'bg-muted font-medium',
                                                    )}
                                                >
                                                    {Icon ? <Icon className="size-4" /> : null}
                                                    {item.label}
                                                </Link>
                                                {item.children?.map((child) => {
                                                    const ChildIcon = child.icon;

                                                    return (
                                                        <Link
                                                            key={child.label}
                                                            href={child.href}
                                                            onClick={() => setOpen(false)}
                                                            className={cn(
                                                                'flex items-center gap-2 rounded-md py-1.5 pr-2 pl-8 text-sm hover:bg-muted',
                                                                child.isActive && 'bg-muted font-medium',
                                                            )}
                                                        >
                                                            {ChildIcon ? <ChildIcon className="size-4" /> : null}
                                                            {child.label}
                                                        </Link>
                                                    );
                                                })}
                                            </Fragment>
                                        );
                                    })}
                                </div>
                            </div>
                        ))}
                    </nav>
                </ScrollArea>
            </SheetContent>
        </Sheet>
    );
}

function NavUser({
    user,
    menuItems,
}: {
    user: ShellUser;
    menuItems: ShellUserMenuItem[];
}) {
    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="ghost" className="gap-2 px-2">
                    <Avatar className="size-8">
                        {user.avatar ? <AvatarImage src={user.avatar} alt={user.name} /> : null}
                        <AvatarFallback>{initials(user.name)}</AvatarFallback>
                    </Avatar>
                    {(user.unreadNotificationsCount ?? 0) > 0 ? (
                        <span className="rounded-full bg-primary px-2 py-0.5 text-xs text-primary-foreground">
                            {user.unreadNotificationsCount}
                        </span>
                    ) : null}
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
                            {Icon ? <Icon /> : null}
                            {item.label}
                        </>
                    );

                    if (item.external) {
                        return (
                            <DropdownMenuItem key={item.label} asChild>
                                <a href={item.href} className="flex items-center gap-2">
                                    {content}
                                </a>
                            </DropdownMenuItem>
                        );
                    }

                    if (item.method === 'post') {
                        return (
                            <DropdownMenuItem key={item.label} asChild>
                                <Link
                                    href={item.href}
                                    method="post"
                                    as="button"
                                    className="flex w-full items-center gap-2"
                                >
                                    {Icon ? <Icon /> : <SignOutIcon />}
                                    {item.label}
                                </Link>
                            </DropdownMenuItem>
                        );
                    }

                    return (
                        <DropdownMenuItem key={item.label} asChild>
                            <Link href={item.href} className="flex items-center gap-2">
                                {content}
                            </Link>
                        </DropdownMenuItem>
                    );
                })}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}

export function ApplicationShell3({
    children,
    className,
    title,
    logo,
    navGroups,
    user,
    userMenuItems = [],
    loginHref,
}: ApplicationShell3Props) {
    return (
        <div className={cn('flex min-h-svh flex-col bg-background', className)}>
            <header className="sticky top-0 z-50 border-b bg-background">
                <div className="flex h-14 items-center gap-4 px-4 lg:px-6">
                    <MobileNav logo={logo} navGroups={navGroups} />

                    <Link href={logo.href} className="flex items-center gap-2">
                        <img src={logo.src} alt={logo.alt} className="h-8 w-auto" />
                        <span className="hidden font-semibold sm:inline">{logo.title}</span>
                    </Link>

                    <nav className="ml-4 hidden items-center gap-1 md:flex">
                        {navGroups.map((group) => (
                            <NavDropdown key={group.title} group={group} />
                        ))}
                    </nav>

                    <div className="ml-auto flex items-center gap-2">
                        {user ? (
                            <NavUser user={user} menuItems={userMenuItems} />
                        ) : loginHref ? (
                            <Button asChild variant="ghost">
                                <Link href={loginHref}>Log in</Link>
                            </Button>
                        ) : null}
                    </div>
                </div>
            </header>

            {title ? (
                <div className="border-b bg-background">
                    <div className="px-4 py-6 lg:px-6">
                        <h1 className="text-xl font-semibold leading-tight text-foreground">{title}</h1>
                    </div>
                </div>
            ) : null}

            <main className="flex flex-1 flex-col">{children as ReactNode}</main>
        </div>
    );
}
