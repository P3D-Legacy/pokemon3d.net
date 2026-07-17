import { Head, Link } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { create, index as resourceIndex } from '@/routes/resource';
import type { Paginated } from '@/types';

type ResourceCard = {
    uuid: string;
    name: string;
    brief: string;
    version: string;
    category: string;
    rating: { average: number; stars: number; count: number };
    likes: number;
    downloads: number;
    updated_at: string;
    created_at: string;
    author: { username: string; name: string; profile_photo_url: string };
    url: string;
};

type CategoryItem = {
    id: number;
    name: string;
    slug: string;
    url: string;
    active: boolean;
    children: Array<{
        id: number;
        name: string;
        slug: string;
        url: string;
        active: boolean;
    }>;
};

type Props = {
    resources: Paginated<ResourceCard>;
    categories: CategoryItem[];
    selectedCategory: { name: string; slug: string } | null;
    canCreate: boolean;
    copy: {
        title: string;
        categories: string;
        allCategories: string;
        wantToAdd: string;
        create: string;
        rating: string;
        likes: string;
        downloads: string;
        updated: string;
        nothingFound: string;
    };
};

function Stars({ count }: { count: number }) {
    return (
        <span className="inline-flex text-yellow-500" aria-hidden>
            {Array.from({ length: 5 }, (_, index) => (
                <span key={index} className={index < count ? 'opacity-100' : 'opacity-30'}>
                    ★
                </span>
            ))}
        </span>
    );
}

export default function ResourcesIndex({ resources, categories, selectedCategory, canCreate, copy }: Props) {
    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <nav className="mb-6 text-sm text-slate-500">
                    {selectedCategory ? (
                        <>
                            <Link href={resourceIndex.url()} className="hover:underline">
                                {copy.title}
                            </Link>
                            <span className="mx-2">/</span>
                            <span className="text-slate-700 dark:text-slate-300">{selectedCategory.name}</span>
                        </>
                    ) : (
                        <span className="text-slate-700 dark:text-slate-300">{copy.title}</span>
                    )}
                </nav>

                <div className="grid grid-cols-1 gap-4 sm:grid-cols-3 md:grid-cols-4">
                    <aside>
                        <div className="overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900 dark:shadow-slate-700">
                            <div className="border-b px-4 py-3 dark:border-slate-700">
                                <h3 className="font-medium text-slate-900 dark:text-white">{copy.categories}</h3>
                            </div>
                            <div className="flex flex-col divide-y text-slate-900 dark:divide-slate-700 dark:text-white">
                                <Link
                                    href={resourceIndex.url()}
                                    className={`px-4 py-3 text-xs hover:bg-black/10 dark:hover:bg-white/10 ${! selectedCategory ? 'bg-green-400/20 font-bold' : ''}`}
                                >
                                    {copy.allCategories}
                                </Link>
                                {categories.map((item) => (
                                    <div key={item.id}>
                                        <Link
                                            href={item.url}
                                            className={`block px-4 py-3 text-xs hover:bg-black/10 dark:hover:bg-white/10 ${item.active ? 'bg-green-400/20 font-bold' : ''}`}
                                        >
                                            {item.name}
                                        </Link>
                                        {item.children.map((child) => (
                                            <Link
                                                key={child.id}
                                                href={child.url}
                                                className={`block px-4 py-3 text-xs hover:bg-black/10 dark:hover:bg-white/10 ${child.active ? 'bg-green-400/20 font-bold' : ''}`}
                                            >
                                                - {child.name}
                                            </Link>
                                        ))}
                                    </div>
                                ))}
                            </div>
                        </div>
                    </aside>

                    <div className="sm:col-span-2 md:col-span-3">
                        {canCreate && (
                            <div className="mb-6 flex items-center justify-between rounded-lg bg-white px-6 py-4 shadow-md dark:bg-slate-900">
                                <span className="font-semibold text-slate-900 dark:text-slate-200">{copy.wantToAdd}</span>
                                <Button asChild variant="brand" size="sm">
                                    <Link href={create.url()}>{copy.create}</Link>
                                </Button>
                            </div>
                        )}

                        <div className="overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900 dark:shadow-slate-700">
                            <div className="flex flex-col divide-y dark:divide-slate-700">
                                {resources.data.map((resource) => (
                                    <Link key={resource.uuid} href={resource.url} className="flex hover:bg-green-400/10">
                                        <div className="grid w-full grid-rows-2 items-center gap-4 p-3 md:flex md:grid-rows-none md:gap-0 sm:p-4">
                                            <div className="mr-4 hidden h-10 w-10 flex-col items-center justify-center md:flex">
                                                <img
                                                    alt={resource.author.name}
                                                    src={resource.author.profile_photo_url}
                                                    className="mx-auto h-10 w-10 rounded-full object-cover"
                                                />
                                            </div>
                                            <div className="flex-1 md:mr-16 md:pl-1">
                                                <div className="font-medium dark:text-white">
                                                    {resource.name}{' '}
                                                    <span className="text-slate-400 dark:text-slate-500">{resource.version}</span>
                                                </div>
                                                <div className="text-sm text-slate-400 dark:text-slate-200">
                                                    {resource.author.username} · {resource.created_at} · {resource.category}
                                                </div>
                                                <div className="truncate text-xs text-slate-500 dark:text-slate-300">
                                                    {resource.brief}
                                                </div>
                                            </div>
                                            <div className="flex flex-col justify-center text-xs text-slate-400">
                                                <div className="flex flex-row justify-between gap-4">
                                                    <span>{copy.rating}:</span>
                                                    <span className="flex items-center gap-1">
                                                        <Stars count={resource.rating.stars} />
                                                        {resource.rating.average} ({resource.rating.count})
                                                    </span>
                                                </div>
                                                <div className="flex flex-row justify-between gap-4">
                                                    <span>{copy.likes}:</span>
                                                    <span>{resource.likes}</span>
                                                </div>
                                                <div className="flex flex-row justify-between gap-4">
                                                    <span>{copy.downloads}:</span>
                                                    <span>{resource.downloads}</span>
                                                </div>
                                                <div className="flex flex-row justify-between gap-4">
                                                    <span>{copy.updated}:</span>
                                                    <span>{resource.updated_at}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                ))}

                                {resources.data.length === 0 && (
                                    <div className="flex w-full items-center justify-center p-4">
                                        <p className="text-center text-slate-400">{copy.nothingFound}</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        {resources.links.length > 3 && (
                            <div className="mt-5 flex flex-wrap justify-center gap-2 rounded-lg bg-white p-4 dark:bg-slate-800">
                                {resources.links.map((link, index) =>
                                    link.url ? (
                                        <Link
                                            key={`${link.label}-${index}`}
                                            href={link.url}
                                            className={`rounded px-3 py-1 text-sm ${link.active ? 'bg-green-600 text-white' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200'}`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ) : (
                                        <span
                                            key={`${link.label}-${index}`}
                                            className="rounded px-3 py-1 text-sm text-slate-400"
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ),
                                )}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}
