import { Head } from '@inertiajs/react';

import { Blogpost10 } from '@/components/blogpost10';
import { Footer59 } from '@/components/footer59';
import { Navbar17 } from '@/components/navbar17';
import LegalContent from '@markdown/legal.mdx';

type Props = {
    title: string;
    category: string;
    updatedAt: string;
    readTime: string;
};

export default function Legal({ title, category, updatedAt, readTime }: Props) {
    return (
        <>
            <Head title={title} />
            <Blogpost10
                title={title}
                category={category}
                pubDate={updatedAt}
                readTime={readTime}
                header={<Navbar17 variant="light" />}
            >
                <LegalContent />
            </Blogpost10>
            <Footer59 />
        </>
    );
}
