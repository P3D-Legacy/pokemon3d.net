import { Head, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';

import HomeFooter from '@/components/home-footer';
import HomeNav from '@/components/home-nav';
import { download } from '@/routes';
import { index as blogIndex } from '@/routes/blog';
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

function DownloadButton({ info, labels }: { info: DownloadInfo; labels: Props['copy'] }) {
    return (
        <div className="mt-2 inline-block md:mt-6">
            <a
                href={download.url()}
                className="mx-auto inline-flex w-76 items-center justify-center rounded-lg border border-green-300 bg-green-800 px-8 py-4 font-extrabold text-white shadow-2xl shadow-black/50 transition duration-150 hover:-translate-y-1 hover:bg-green-600 lg:mx-0"
            >
                <span>
                    {labels.downloadLabel} {info.version}
                    <sup>&dagger;</sup>
                </span>
            </a>
            <div className="mt-1 mb-2 text-xs text-white drop-shadow shadow-black">
                <span>
                    {labels.releasedLabel} {info.released}
                </span>
                <span className="px-2">&mdash;</span>
                <span>
                    <a href="https://wiki.pokemon3d.net/index.php/Pok%C3%A9mon_3D#Requirements" className="hover:text-slate-300">
                        <sup>&dagger;</sup> {labels.requirementsLabel}
                    </a>
                </span>
            </div>
        </div>
    );
}

function PostPreview({ post }: { post: PostCard }) {
    return (
        <Link
            href={post.url}
            className="relative mx-4 block justify-between overflow-hidden rounded-lg border border-slate-200 bg-white p-4 shadow-md hover:bg-slate-50 sm:mx-0 sm:p-6 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800"
        >
            <span className={`absolute inset-x-0 bottom-0 h-2 ${post.sticky ? 'bg-red-500' : 'bg-green-600'}`} />
            <div className="sm:flex sm:justify-between sm:gap-4">
                <div>
                    <h3 className="text-lg font-bold text-slate-900 sm:text-xl dark:text-slate-100">{post.title}</h3>
                    {post.tag && (
                        <p className="mt-1 text-xs font-medium text-slate-600">
                            <span className="inline-flex items-center justify-center rounded-full bg-green-100 px-2 py-0.5 text-green-700 dark:bg-green-700/80 dark:text-green-100">
                                {post.tag}
                            </span>
                        </p>
                    )}
                </div>
                <div className="hidden sm:block sm:shrink-0">
                    <img
                        alt={post.author.username}
                        src={post.author.profile_photo_url}
                        className="h-12 w-12 rounded-md object-cover shadow-sm"
                    />
                </div>
            </div>
            <div className="mt-4 mb-14">
                <p className="max-w-[40ch] text-sm text-slate-600 dark:text-slate-200">{post.excerpt}</p>
            </div>
            <dl className="absolute bottom-0 mt-6 flex gap-3 pb-6 xl:gap-6">
                <div className="flex flex-col-reverse">
                    <dd className="text-xs text-slate-500 dark:text-slate-300">{post.published_for_humans}</dd>
                </div>
                <div className="flex flex-col-reverse">
                    <dd className="text-xs text-slate-500 dark:text-slate-300">{post.reading_time}</dd>
                </div>
            </dl>
        </Link>
    );
}

export default function Home({ posts, stats, screenshots, download: downloadInfo, copy }: Props) {
    const { appName } = usePage<SharedPageProps>().props;
    const [activeScreenshot, setActiveScreenshot] = useState(0);

    return (
        <>
            <Head title={appName} />
            <HomeNav />

            <div className="container mx-auto h-auto text-white">
                <div className="px-3 text-center lg:px-0">
                    <h1 className="my-4 text-2xl leading-tight font-black md:text-3xl lg:text-5xl">{copy.headline}</h1>
                    <p className="mb-8 text-base leading-normal text-slate-50 md:text-xl lg:text-2xl">{copy.subheadline}</p>
                    <DownloadButton info={downloadInfo} labels={copy} />
                </div>

                <div className="z-auto mx-auto flex w-full content-end items-center overflow-hidden lg:w-11/12">
                    <div className="browser-mockup with-url relative mx-6 my-8 flex flex-1 aspect-video rounded-t rounded-b-lg bg-white shadow-xl shadow-black/50 md:m-12">
                        <video muted controls className="h-full w-full rounded-b-lg object-cover object-center">
                            <source src="https://files.pokemon3d.net/video/trailer.mp4" type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>

            <section className="border-b bg-slate-100 py-12 dark:border-black dark:bg-slate-900">
                <div className="mx-auto grid max-w-full grid-flow-row grid-cols-1 items-center justify-between gap-4 text-4xl font-bold text-slate-900 opacity-75 sm:grid-cols-3 md:text-5xl xl:grid-cols-6 dark:text-slate-100">
                    {stats.map((stat) => (
                        <div key={stat.key} className="px-4 text-center">
                            <div>{stat.value}</div>
                            <div className="mt-2 text-sm font-semibold tracking-wide uppercase opacity-80">{stat.label}</div>
                            {stat.hint ? <div className="text-xs font-normal opacity-70">{stat.hint}</div> : null}
                        </div>
                    ))}
                </div>
            </section>

            <section className="border-b bg-white py-8 dark:border-black dark:bg-slate-800">
                <div className="container mx-auto flex flex-wrap pt-4 pb-12">
                    <h2 className="my-2 w-full text-center text-2xl leading-tight font-black text-slate-800 uppercase sm:text-3xl dark:text-slate-200">
                        {copy.latestNews}
                    </h2>
                    <div className="mb-4 w-full">
                        <div className="mx-auto my-0 h-1 w-64 rounded-t bg-black py-0 opacity-25 dark:bg-white" />
                    </div>
                    <div className="mx-auto grid grid-flow-row grid-cols-1 gap-2 sm:grid-flow-col sm:grid-cols-2 sm:grid-rows-2 xl:grid-cols-4 xl:grid-rows-1">
                        {posts.map((post) => (
                            <PostPreview key={post.uuid} post={post} />
                        ))}
                    </div>
                    <div className="mt-8 w-full text-center text-xs">
                        <Link
                            href={blogIndex()}
                            className="rounded-lg bg-green-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-green-700"
                        >
                            {posts.length > 0 ? copy.readMore : copy.goToBlog}
                        </Link>
                        {posts.length === 0 && <p className="mt-3 dark:text-slate-400">{copy.nothingToShow}...</p>}
                    </div>
                </div>
            </section>

            <section className="px-4 py-8 sm:p-8">
                <div className="container mx-auto m-8 max-w-5xl text-white">
                    <h2 className="my-2 w-full text-center text-2xl leading-tight font-black uppercase sm:text-3xl">
                        {copy.screenshots}
                    </h2>
                    <div className="mb-4 w-full">
                        <div className="mx-auto my-0 h-1 w-64 rounded-t bg-white py-0 opacity-25" />
                    </div>
                    <div className="relative w-full overflow-hidden rounded-xl shadow-xl shadow-black/50">
                        <img
                            src={`/${screenshots[activeScreenshot]?.path}`}
                            alt={screenshots[activeScreenshot]?.title}
                            className="aspect-video w-full object-cover"
                        />
                        <div className="absolute inset-x-0 bottom-0 flex items-center justify-between bg-black/50 px-4 py-3 text-sm">
                            <div>
                                <div className="font-semibold">{screenshots[activeScreenshot]?.title}</div>
                                <div className="opacity-80">{screenshots[activeScreenshot]?.author}</div>
                            </div>
                            <div className="flex gap-2">
                                <button
                                    type="button"
                                    className="rounded bg-white/20 px-3 py-1"
                                    onClick={() =>
                                        setActiveScreenshot((current) => (current === 0 ? screenshots.length - 1 : current - 1))
                                    }
                                >
                                    Prev
                                </button>
                                <button
                                    type="button"
                                    className="rounded bg-white/20 px-3 py-1"
                                    onClick={() => setActiveScreenshot((current) => (current + 1) % screenshots.length)}
                                >
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section className="border-b bg-black/20 p-4 sm:p-8 dark:border-black">
                <div className="container mx-auto m-8 max-w-5xl text-white">
                    <h2 className="my-2 w-full text-center text-2xl leading-tight font-black uppercase sm:text-3xl">
                        {copy.historyTitle}
                    </h2>
                    <div className="mb-4 w-full">
                        <div className="mx-auto my-0 h-1 w-64 rounded-t bg-white py-0 opacity-25" />
                    </div>
                    {copy.history.map((paragraph) => (
                        <p key={paragraph.slice(0, 24)} className="mb-4" dangerouslySetInnerHTML={{ __html: paragraph }} />
                    ))}
                </div>
            </section>

            <section className="border-b bg-white py-8 dark:border-black dark:bg-slate-800">
                <div className="container mx-auto m-8 max-w-5xl">
                    <h2 className="my-2 w-full text-center text-2xl leading-tight font-black text-slate-800 uppercase sm:text-3xl dark:text-slate-200">
                        {copy.featuresTitle}
                    </h2>
                    <div className="mb-4 w-full">
                        <div className="mx-auto my-0 h-1 w-64 rounded-t bg-black py-0 opacity-25 dark:bg-white" />
                    </div>
                    <div className="flex flex-wrap">
                        <div className="w-5/6 p-6 sm:w-1/2">
                            <h3 className="mb-3 text-xl font-bold text-slate-800 sm:text-3xl dark:text-slate-200">
                                {copy.nostalgiaTitle}
                            </h3>
                            <p className="text-slate-600 sm:mb-8 dark:text-slate-400">{copy.nostalgiaBody}</p>
                        </div>
                        <div className="w-full px-4 sm:w-1/2 sm:p-6">
                            <img src="/img/pikachu.png" alt="Pikachu" />
                        </div>
                    </div>
                    <div className="flex flex-col-reverse flex-wrap sm:flex-row">
                        <div className="grid w-full justify-items-end px-4 sm:mt-6 sm:w-1/2 sm:p-6">
                            <img src="/img/rhydon.png" alt="Rhydon" />
                        </div>
                        <div className="mt-6 w-full p-6 sm:w-1/2">
                            <h3 className="mb-3 text-xl font-bold text-slate-800 sm:text-3xl dark:text-slate-200">
                                {copy.generationsTitle}
                            </h3>
                            <p className="mb-8 text-slate-600 dark:text-slate-400">{copy.generationsBody}</p>
                        </div>
                    </div>
                    <div className="flex flex-wrap">
                        <div className="w-5/6 p-6 sm:w-1/2">
                            <h3 className="mb-3 text-xl font-bold text-slate-800 sm:text-3xl dark:text-slate-200">
                                {copy.experienceTitle}
                            </h3>
                            <p className="text-slate-600 sm:mb-8 dark:text-slate-400">{copy.experienceBody}</p>
                        </div>
                        <div className="w-full px-4 sm:w-1/2 sm:p-6">
                            <img src="/img/scizor.png" alt="Scizor" />
                        </div>
                    </div>
                </div>
            </section>

            <section className="w-full bg-black/20 px-3 py-12 text-center text-white">
                <h2 className="my-2 w-full text-center text-2xl leading-tight font-black uppercase sm:text-3xl">{copy.ctaTitle}</h2>
                <div className="mb-4 w-full">
                    <div className="mx-auto my-0 h-1 w-1/6 rounded-t bg-white py-0 opacity-25" />
                </div>
                <h3 className="my-4 text-xl font-extrabold text-slate-100 sm:text-2xl">{copy.ctaSubtitle}</h3>
                <DownloadButton info={downloadInfo} labels={copy} />
            </section>

            <HomeFooter />
        </>
    );
}
