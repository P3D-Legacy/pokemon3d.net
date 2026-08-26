import { cn } from '@/lib/utils';

interface HistorySectionProps {
    heading: string;
    paragraphs: string[];
    className?: string;
}

const HistorySection = ({ heading, paragraphs, className }: HistorySectionProps) => {
    return (
        <section className={cn('border-b bg-background py-16 md:py-24', className)}>
            <div className="container mx-auto px-4">
                <div className="mx-auto mb-12 max-w-2xl text-center md:mb-16">
                    <h2 className="text-4xl font-semibold tracking-tight md:text-5xl">{heading}</h2>
                </div>

                <div className="prose prose-neutral mx-auto max-w-3xl dark:prose-invert">
                    {paragraphs.map((paragraph) => (
                        <p
                            key={paragraph.slice(0, 48)}
                            className="text-base leading-relaxed text-muted-foreground md:text-lg [&_a]:font-medium [&_a]:text-primary [&_a]:underline-offset-4 hover:[&_a]:underline"
                            dangerouslySetInnerHTML={{ __html: paragraph }}
                        />
                    ))}
                </div>
            </div>
        </section>
    );
};

export { HistorySection };
