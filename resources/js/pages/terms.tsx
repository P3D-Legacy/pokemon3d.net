import { Head } from '@inertiajs/react';

import { Blogpost10 } from '@/components/blogpost10';

type Props = {
    title: string;
    category: string;
    updatedAt: string;
    readTime: string;
    html: string;
};

export default function Terms({ title, category, updatedAt, readTime, html }: Props) {
    return (
        <>
            <Head title={title} />

            <Blogpost10 title={title} category={category} pubDate={updatedAt} readTime={readTime} html={html} />
        </>
    );
}
