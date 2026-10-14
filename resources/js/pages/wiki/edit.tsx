import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, BookOpenIcon } from '@phosphor-icons/react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

type Props = {
    entry: {
        title: string;
        slug: string;
        body: string;
        published: boolean;
        tags: string;
    };
    copy: {
        title: string;
        wiki: string;
        name: string;
        body: string;
        tags: string;
        tagsHelp: string;
        published: string;
        summary: string;
        submit: string;
        cancel: string;
        markdownHelp: string;
    };
};

export default function WikiEdit({ entry, copy }: Props) {
    return (
        <>
            <Head title={copy.title} />

            <div className="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-8 flex flex-col gap-3">
                    <Button variant="ghost" size="sm" className="w-fit px-0" asChild>
                        <Link href={`/wiki/${entry.slug}`}>
                            <ArrowLeftIcon data-icon="inline-start" />
                            {entry.title}
                        </Link>
                    </Button>
                    <div className="flex items-center gap-2 text-muted-foreground">
                        <BookOpenIcon className="size-5" weight="fill" />
                        <span className="text-sm">{copy.wiki}</span>
                    </div>
                    <h1 className="text-3xl font-semibold tracking-tight">{copy.title}</h1>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="text-base font-semibold">{copy.title}</CardTitle>
                        <CardDescription>{copy.markdownHelp}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form action={`/wiki/${entry.slug}`} method="put" className="flex flex-col gap-4">
                            {({ processing, errors }) => (
                                <>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="title">{copy.name}</Label>
                                        <Input id="title" name="title" required defaultValue={entry.title} />
                                        <InputError message={errors.title} />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="body">{copy.body}</Label>
                                        <Textarea
                                            id="body"
                                            name="body"
                                            required
                                            className="min-h-56"
                                            defaultValue={entry.body}
                                        />
                                        <InputError message={errors.body} />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="tags">{copy.tags}</Label>
                                        <Input id="tags" name="tags" defaultValue={entry.tags} />
                                        <p className="text-xs text-muted-foreground">{copy.tagsHelp}</p>
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <Label htmlFor="summary">{copy.summary}</Label>
                                        <Input id="summary" name="summary" />
                                    </div>
                                    <label className="flex items-center gap-2 text-sm">
                                        <input type="hidden" name="published" value="0" />
                                        <input
                                            type="checkbox"
                                            name="published"
                                            value="1"
                                            defaultChecked={entry.published}
                                        />
                                        {copy.published}
                                    </label>
                                    <div className="flex flex-wrap justify-end gap-2">
                                        <Button type="button" variant="outline" asChild>
                                            <Link href={`/wiki/${entry.slug}`}>{copy.cancel}</Link>
                                        </Button>
                                        <Button type="submit" disabled={processing}>
                                            {copy.submit}
                                        </Button>
                                    </div>
                                </>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
