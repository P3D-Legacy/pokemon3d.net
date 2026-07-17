import { Form, Head, Link } from '@inertiajs/react';
import { useState } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { index as resourceIndex, uuid as resourceShow } from '@/routes/resource';
import { store as storeRating } from '@/routes/resource/rate';

type Props = {
    resource: {
        uuid: string;
        name: string;
    };
    copy: {
        resources: string;
        title: string;
        clickToRate: string;
        reviewLabel: string;
        reviewPlaceholder: string;
        minMax: string;
        cancel: string;
        submit: string;
        labels: Record<number, string>;
    };
};

export default function ResourceRate({ resource, copy }: Props) {
    const [rating, setRating] = useState(0);
    const [body, setBody] = useState('');

    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <nav className="mb-6 text-sm text-slate-500">
                    <Link href={resourceIndex.url()} className="hover:underline">
                        {copy.resources}
                    </Link>
                    <span className="mx-2">/</span>
                    <Link href={resourceShow.url(resource.uuid)} className="hover:underline">
                        {resource.name}
                    </Link>
                    <span className="mx-2">/</span>
                    <span className="text-slate-700 dark:text-slate-300">{copy.title}</span>
                </nav>

                <div className="mx-auto max-w-2xl">
                    <div className="rounded-lg bg-white px-6 py-6 shadow-md dark:bg-slate-900">
                        <Form {...storeRating.form(resource.uuid)} className="space-y-6">
                            {({ processing, errors }) => (
                                <>
                                    <div className="rounded-lg bg-slate-50 p-6 dark:bg-slate-800">
                                        <h1 className="mb-4 text-center text-lg font-semibold text-slate-900 dark:text-white">
                                            {copy.title}
                                        </h1>
                                        <div className="flex flex-col items-center space-y-4">
                                            <div className="flex space-x-1">
                                                {[1, 2, 3, 4, 5].map((value) => (
                                                    <button
                                                        key={value}
                                                        type="button"
                                                        title={copy.labels[value]}
                                                        onClick={() => setRating(value)}
                                                        className={`h-10 w-10 rounded-sm p-1 transition-colors ${rating >= value ? 'text-yellow-500' : 'text-gray-400'}`}
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" className="h-full w-full" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    </button>
                                                ))}
                                            </div>
                                            <p className="text-slate-500 dark:text-slate-400">
                                                {rating > 0 ? copy.labels[rating] : copy.clickToRate}
                                            </p>
                                        </div>
                                        <input type="hidden" name="rating" value={rating || ''} />
                                        <InputError message={errors.rating} className="mt-2" />
                                    </div>

                                    <div>
                                        <Label htmlFor="body" className="mb-2">
                                            {copy.reviewLabel}
                                        </Label>
                                        <textarea
                                            id="body"
                                            name="body"
                                            rows={4}
                                            required
                                            minLength={10}
                                            maxLength={255}
                                            placeholder={copy.reviewPlaceholder}
                                            value={body}
                                            onChange={(event) => setBody(event.target.value)}
                                            className="mt-2 block w-full rounded-md border border-slate-300 bg-transparent px-3 py-2 text-sm dark:border-slate-600 dark:text-white"
                                        />
                                        <div className="mt-1 flex justify-between text-xs text-slate-500">
                                            <span>{copy.minMax}</span>
                                            <span>
                                                {body.length}/255
                                            </span>
                                        </div>
                                        <InputError message={errors.body} className="mt-2" />
                                    </div>

                                    <div className="flex justify-end gap-3">
                                        <Button asChild variant="outline">
                                            <Link href={resourceShow.url(resource.uuid)}>{copy.cancel}</Link>
                                        </Button>
                                        <Button type="submit" variant="brand" disabled={processing || rating < 1}>
                                            {copy.submit}
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </div>
                </div>
            </div>
        </>
    );
}
