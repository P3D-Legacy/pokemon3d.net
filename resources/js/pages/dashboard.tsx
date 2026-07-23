import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRightIcon,
    BookOpenIcon,
    DiscordLogoIcon,
    DownloadSimpleIcon,
    HouseIcon,
    PackageIcon,
    SmileyIcon,
} from '@phosphor-icons/react';
import type { ComponentType, SVGProps } from 'react';

import { Button } from '@/components/ui/button';
import { Card, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import type { SharedPageProps } from '@/types';

type IconComponent = ComponentType<SVGProps<SVGSVGElement>>;

type Props = {
    copy: {
        welcome: string;
        intro: string[];
        documentation: string;
        documentationBody: string;
        exploreWiki: string;
        discord: string;
        discordBody: string;
        getDiscord: string;
        customSkin: string;
        customSkinBody: string;
        getCustomization: string;
        resources: string;
        resourcesBody: string;
        browseResources: string;
        downloadLabel: string;
        exploreLabel: string;
    };
    links: {
        wiki: string;
        discord: string;
        skinHome: string;
        resources: string;
        download: string;
    };
    author: {
        name: string;
        url: string;
    };
};

function IntroParagraph({ text, author }: { text: string; author: Props['author'] }) {
    const parts = text.split(author.name);

    if (parts.length < 2) {
        return <p>{text}</p>;
    }

    return (
        <p>
            {parts[0]}
            <a
                href={author.url}
                target="_blank"
                rel="noreferrer"
                className="font-medium text-primary underline-offset-4 hover:underline"
            >
                {author.name}
            </a>
            {parts.slice(1).join(author.name)}
        </p>
    );
}

function FeatureCard({
    icon: Icon,
    title,
    body,
    href,
    cta,
}: {
    icon: IconComponent;
    title: string;
    body: string;
    href: string;
    cta: string;
}) {
    return (
        <Card className="h-full transition-colors hover:bg-muted/30">
            <CardHeader className="flex flex-row items-start gap-3">
                <div className="bg-primary/10 p-2.5 text-primary">
                    <Icon className="size-5" weight="fill" />
                </div>
                <div className="min-w-0 flex-1">
                    <CardTitle className="text-base font-semibold">{title}</CardTitle>
                    <CardDescription className="mt-1.5 text-sm leading-relaxed">{body}</CardDescription>
                </div>
            </CardHeader>
            <CardFooter className="mt-auto border-t-0 pt-0">
                <Button variant="link" size="sm" className="h-auto px-0" asChild>
                    <Link href={href} className="inline-flex items-center gap-1">
                        {cta}
                        <ArrowRightIcon className="size-3.5 transition-transform group-hover/card:translate-x-0.5" />
                    </Link>
                </Button>
            </CardFooter>
        </Card>
    );
}

export default function Dashboard({ copy, links, author }: Props) {
    const { auth } = usePage<SharedPageProps>().props;
    const username = auth.user?.username;

    const features: Array<{
        icon: IconComponent;
        title: string;
        body: string;
        href: string;
        cta: string;
    }> = [
        {
            icon: BookOpenIcon,
            title: copy.documentation,
            body: copy.documentationBody,
            href: links.wiki,
            cta: copy.exploreWiki,
        },
        {
            icon: DiscordLogoIcon,
            title: copy.discord,
            body: copy.discordBody,
            href: links.discord,
            cta: copy.getDiscord,
        },
        {
            icon: SmileyIcon,
            title: copy.customSkin,
            body: copy.customSkinBody,
            href: links.skinHome,
            cta: copy.getCustomization,
        },
        {
            icon: PackageIcon,
            title: copy.resources,
            body: copy.resourcesBody,
            href: links.resources,
            cta: copy.browseResources,
        },
    ];

    return (
        <>
            <Head title="Dashboard" />

            <div className="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div className="flex max-w-3xl flex-col gap-3">
                        <div className="flex items-center gap-2 text-muted-foreground">
                            <HouseIcon className="size-5" weight="fill" />
                            <span className="text-sm">Dashboard</span>
                        </div>
                        <h1 className="text-3xl font-semibold tracking-tight">{copy.welcome}</h1>
                        {username ? (
                            <p className="text-sm text-muted-foreground">
                                Signed in as <span className="font-medium text-foreground">{username}</span>
                            </p>
                        ) : null}
                        <div className="flex flex-col gap-3 text-sm leading-relaxed text-muted-foreground">
                            {copy.intro.map((paragraph) => (
                                <IntroParagraph key={paragraph.slice(0, 48)} text={paragraph} author={author} />
                            ))}
                        </div>
                    </div>

                    <Button size="xl" asChild>
                        <Link href={links.download}>
                            <DownloadSimpleIcon data-icon="inline-start" weight="fill" />
                            {copy.downloadLabel}
                        </Link>
                    </Button>
                </div>

                <div className="mb-4 flex items-center justify-between gap-3">
                    <h2 className="text-sm font-medium text-muted-foreground">{copy.exploreLabel}</h2>
                </div>

                <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                    {features.map((feature) => (
                        <FeatureCard key={feature.title} {...feature} />
                    ))}
                </div>
            </div>
        </>
    );
}
