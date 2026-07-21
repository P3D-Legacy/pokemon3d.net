import { Link, usePage } from '@inertiajs/react';
import { DiscordLogoIcon, GithubLogoIcon } from '@phosphor-icons/react';
import type { ReactNode } from 'react';

import { cn } from '@/lib/utils';
import { contact, discord, github, home, legal } from '@/routes';
import { index as blogIndex } from '@/routes/blog';
import { show as policyShow } from '@/routes/policy';
import { show as termsShow } from '@/routes/terms';
import type { SharedPageProps } from '@/types';

type FooterLink = {
    name: string;
    href: string;
};

type FooterSocialLink = {
    icon: ReactNode;
    href: string;
    label: string;
};

interface Footer59Props {
    className?: string;
}

const Footer59 = ({ className }: Footer59Props) => {
    const { appName } = usePage<SharedPageProps>().props;

    const quickLinks: FooterLink[] = [
        { name: 'Blog', href: blogIndex.url() },
        { name: 'Contact', href: contact.url() },
        { name: 'Legal', href: legal.url() },
    ];

    const legalLinks: FooterLink[] = [
        { name: 'Terms and Conditions', href: termsShow.url() },
        { name: 'Privacy Policy', href: policyShow.url() },
    ];

    const socialLinks: FooterSocialLink[] = [
        {
            icon: <DiscordLogoIcon weight="fill" />,
            href: discord.url(),
            label: 'Discord',
        },
        {
            icon: <GithubLogoIcon weight="fill" />,
            href: github.url(),
            label: 'GitHub',
        },
    ];

    return (
        <section className={cn('border-t bg-background py-8 lg:py-10', className)}>
            <div className="container mx-auto max-w-7xl px-4">
                <footer className="flex flex-col gap-6">
                    <div className="flex flex-col items-center gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <Link href={home()} className="inline-flex shrink-0">
                            <img src="/img/pokemon3d_logo_sm.png" alt={appName} className="h-8 w-auto" />
                        </Link>

                        <nav
                            aria-label="Footer"
                            className="flex w-full flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-medium text-muted-foreground lg:flex-1 lg:justify-center"
                        >
                            {quickLinks.map((link) => (
                                <Link key={link.name} href={link.href} className="hover:text-primary">
                                    {link.name}
                                </Link>
                            ))}
                        </nav>

                        <ul className="flex w-full flex-wrap items-center justify-center gap-4 text-sm text-muted-foreground lg:w-auto lg:justify-end">
                            {socialLinks.map((social) => (
                                <li key={social.label} className="font-medium hover:text-primary">
                                    <Link
                                        href={social.href}
                                        className="inline-flex items-center gap-2"
                                        aria-label={social.label}
                                    >
                                        <span className="[&_svg]:size-4">{social.icon}</span>
                                        <span>{social.label}</span>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div className="flex w-full flex-col items-center gap-3 text-xs font-medium text-muted-foreground lg:flex-row lg:justify-between">
                        <p className="max-w-xl text-center lg:text-left">
                            {appName} is not affiliated with Nintendo, Creatures Inc. or GAME FREAK Inc. Owned and
                            operated by{' '}
                            <a href="https://kilobyte.no/" className="underline hover:text-primary">
                                Kilobyte AS
                            </a>
                            .
                        </p>
                        <ul className="flex flex-wrap justify-center gap-x-4 gap-y-1 lg:justify-end">
                            {legalLinks.map((link) => (
                                <li key={link.name} className="underline hover:text-primary">
                                    <Link href={link.href}>{link.name}</Link>
                                </li>
                            ))}
                        </ul>
                    </div>
                </footer>
            </div>
        </section>
    );
};

export { Footer59 };
