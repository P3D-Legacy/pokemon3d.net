import { Head, usePage } from '@inertiajs/react';
import { CaretLeftIcon, CaretRightIcon } from '@phosphor-icons/react';
import { useState } from 'react';

import { Blog34 } from '@/components/blog34';
import { Cta16 } from '@/components/cta16';
import { Feature138 } from '@/components/feature138';
import { Footer59 } from '@/components/footer59';
import { Hero8 } from '@/components/hero8';
import { HistorySection } from '@/components/history-section';
import { Navbar17 } from '@/components/navbar17';
import { HomeStatsSection } from '@/components/stats-card5';
import { download } from '@/routes';
import type { HomeStat, PostCard, SharedPageProps } from '@/types';

type Screenshot = {
    title: string;
    path: string;
    author: string;
};

type DownloadInfo = {
    version: string;
    released: string;
};

type Props = {
    posts: PostCard[];
    stats: HomeStat[];
    screenshots: Screenshot[];
    download: DownloadInfo;
    copy: {
        headline: string;
        subheadline: string;
        latestNews: string;
        readMore: string;
        nothingToShow: string;
        goToBlog: string;
        screenshots: string;
        historyTitle: string;
        history: string[];
        featuresTitle: string;
        nostalgiaTitle: string;
        nostalgiaBody: string;
        generationsTitle: string;
        generationsBody: string;
        experienceTitle: string;
        experienceBody: string;
        mediaTitle: string;
        ctaTitle: string;
        ctaSubtitle: string;
        downloadLabel: string;
        releasedLabel: string;
        requirementsLabel: string;
    };
};

export default function Home({ posts, stats, screenshots, download: downloadInfo, copy }: Props) {
    const { appName } = usePage<SharedPageProps>().props;
    const [activeScreenshot, setActiveScreenshot] = useState(0);

    return (
        <>
            <Head title={appName} />

            <Hero8
                heading={copy.headline}
                description={copy.subheadline}
                downloadMeta={{
                    version: downloadInfo.version,
                    released: downloadInfo.released,
                    downloadLabel: copy.downloadLabel,
                    releasedLabel: copy.releasedLabel,
                    requirementsLabel: copy.requirementsLabel,
                }}
            >
                <Navbar17 />
            </Hero8>

            <HomeStatsSection stats={stats} />

            <Blog34
                heading={copy.latestNews}
                posts={posts}
                readMoreLabel={copy.readMore}
                goToBlogLabel={copy.goToBlog}
                nothingToShowLabel={copy.nothingToShow}
            />

            <section className="px-4 py-8 sm:p-8">
                <div className="container mx-auto m-8 max-w-5xl text-white">
                    <h2 className="my-2 w-full text-center text-2xl leading-tight font-black uppercase sm:text-3xl">
                        {copy.screenshots}
                    </h2>
                    <div className="mb-4 w-full">
                        <div className="mx-auto my-0 h-1 w-64 rounded-t bg-white py-0 opacity-25" />
                    </div>
                    <div className="relative w-full overflow-hidden shadow-md">
                        <img
                            src={`/${screenshots[activeScreenshot]?.path}`}
                            alt={screenshots[activeScreenshot]?.title}
                            className="aspect-video w-full object-cover"
                        />
                        <div className="absolute inset-x-0 bottom-0 flex items-center justify-between bg-black/50 px-4 py-3 text-sm">
                            <div>
                                <div className="font-semibold">{screenshots[activeScreenshot]?.title}</div>
                                <div className="opacity-80">Screenshot by {screenshots[activeScreenshot]?.author}</div>
                            </div>
                            <div className="flex gap-2">
                                <button
                                    type="button"
                                    className="inline-flex items-center gap-1 rounded bg-white/20 px-3 py-1"
                                    onClick={() =>
                                        setActiveScreenshot((current) => (current === 0 ? screenshots.length - 1 : current - 1))
                                    }
                                >
                                    <CaretLeftIcon className="size-4" />
                                    Previous
                                </button>
                                <button
                                    type="button"
                                    className="inline-flex items-center gap-1 rounded bg-white/20 px-3 py-1"
                                    onClick={() => setActiveScreenshot((current) => (current + 1) % screenshots.length)}
                                >
                                    Next
                                    <CaretRightIcon className="size-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <HistorySection heading={copy.historyTitle} paragraphs={copy.history} />

            <Feature138
                heading={copy.featuresTitle}
                features={[
                    {
                        title: copy.nostalgiaTitle,
                        body: copy.nostalgiaBody,
                        image: { src: '/img/pikachu.png', alt: 'Pikachu' },
                    },
                    {
                        title: copy.generationsTitle,
                        body: copy.generationsBody,
                        image: { src: '/img/rhydon.png', alt: 'Rhydon' },
                    },
                    {
                        title: copy.experienceTitle,
                        body: copy.experienceBody,
                        image: { src: '/img/scizor.png', alt: 'Scizor' },
                    },
                ]}
            />

            <Cta16
                heading={copy.ctaTitle}
                description={copy.ctaSubtitle}
                button={{
                    text: `${copy.downloadLabel} ${downloadInfo.version}`,
                    url: download.url(),
                }}
                downloadMeta={{
                    released: downloadInfo.released,
                    releasedLabel: copy.releasedLabel,
                    requirementsLabel: copy.requirementsLabel,
                }}
            />

            <Footer59 />
        </>
    );
}
