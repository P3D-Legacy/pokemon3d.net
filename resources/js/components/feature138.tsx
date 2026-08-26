import { cn } from '@/lib/utils';

type FeatureItem = {
    title: string;
    body: string;
    image: {
        src: string;
        alt: string;
    };
};

interface Feature138Props {
    heading: string;
    description?: string;
    features: [FeatureItem, FeatureItem, FeatureItem];
    className?: string;
}

const Feature138 = ({ heading, description, features, className }: Feature138Props) => {
    return (
        <section className={cn('border-b bg-background py-16 md:py-24', className)}>
            <div className="container mx-auto px-4">
                <div className="mx-auto mb-12 max-w-2xl text-center md:mb-16">
                    <h2 className="text-4xl font-semibold tracking-tight md:text-5xl">{heading}</h2>
                    {description ? <p className="mt-3 text-xl font-medium text-muted-foreground">{description}</p> : null}
                </div>

                {features.map((feature, index) => {
                    const imageFirst = index % 2 === 0;

                    return (
                        <div
                            key={feature.title}
                            className="mt-8 flex flex-col overflow-hidden bg-muted/50 first:mt-0 md:mt-16 md:flex-row md:first:mt-0"
                        >
                            {imageFirst ? (
                                <>
                                    <FeatureImage image={feature.image} />
                                    <FeatureCopy title={feature.title} body={feature.body} />
                                </>
                            ) : (
                                <>
                                    <FeatureCopy title={feature.title} body={feature.body} />
                                    <FeatureImage image={feature.image} />
                                </>
                            )}
                        </div>
                    );
                })}
            </div>
        </section>
    );
};

function FeatureImage({ image }: { image: FeatureItem['image'] }) {
    return (
        <div className="flex w-full items-center justify-center bg-muted/50 p-6 md:w-1/2">
            <img src={image.src} alt={image.alt} className="max-h-64 w-full object-contain" />
        </div>
    );
}

function FeatureCopy({ title, body }: { title: string; body: string }) {
    return (
        <div className="flex w-full flex-col justify-center gap-6 px-8 py-7 md:w-1/2 md:px-12 md:py-10">
            <h3 className="text-lg font-semibold md:text-2xl">{title}</h3>
            <div className="h-px w-full bg-muted-foreground/40" />
            <p className="text-muted-foreground">{body}</p>
        </div>
    );
}

export { Feature138 };
