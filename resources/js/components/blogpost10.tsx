import { format } from 'date-fns';
import { useEffect, useState, type ReactNode } from 'react';

import { cn } from '@/lib/utils';

type Blogpost10Props = {
    className?: string;
    title: string;
    category?: string;
    pubDate?: Date | string;
    readTime?: string;
    children?: ReactNode;
    header?: ReactNode;
    html?: string;
};

const Blogpost10 = ({ className, title, category, pubDate, readTime, children, header, html }: Blogpost10Props) => {
    const [progress, setProgress] = useState(0);
    const date = pubDate ? (pubDate instanceof Date ? pubDate : new Date(pubDate)) : null;

    useEffect(() => {
        const onScroll = () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const next = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            setProgress(Math.min(100, Math.max(0, next)));
        };

        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });

        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    return (
        <section className={cn('relative min-h-screen bg-background', className)}>
            <div className="sticky top-0 z-40">
                {header}
                <div className="h-1 bg-muted" aria-hidden="true">
                    <div className="h-full bg-primary transition-[width] duration-150" style={{ width: `${progress}%` }} />
                </div>
            </div>

            <div className="container mx-auto px-4 py-16 md:py-24">
                <div className="mx-auto max-w-xl">
                    {category ? (
                        <p className="text-xs font-medium tracking-widest text-muted-foreground uppercase">{category}</p>
                    ) : null}
                    <h1 className="mt-4 text-3xl leading-tight font-semibold text-balance md:text-4xl">{title}</h1>
                    {(readTime || date) && (
                        <p className="mt-3 text-sm text-muted-foreground">
                            {readTime}
                            {readTime && date ? ' · ' : null}
                            {date ? format(date, 'd MMMM yyyy') : null}
                        </p>
                    )}

                    {html ? (
                        <article className="prose mt-10 dark:prose-invert" dangerouslySetInnerHTML={{ __html: html }} />
                    ) : (
                        <article className="prose mt-10 dark:prose-invert">{children}</article>
                    )}
                </div>
            </div>
        </section>
    );
};

export { Blogpost10 };
