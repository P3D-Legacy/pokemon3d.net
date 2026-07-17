import { Form, Link, router } from '@inertiajs/react';
import { useState } from 'react';

import {
    apply,
    destroy,
    edit,
    like,
    show,
} from '@/actions/App/Http/Controllers/Skin/SkinController';
import { destroy as destroyUploaded } from '@/actions/App/Http/Controllers/Skin/UploadedSkinController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

export type SkinCardData = {
    uuid: string;
    name: string;
    public: boolean;
    owner_id: number;
    image_url: string;
    file_size: string;
    likes_count: number;
    liked: boolean;
    is_owner: boolean;
    uploaded_at: string;
    publisher: { username: string; url: string } | null;
    show_url: string | null;
};

type Props = {
    skin: SkinCardData;
    mode?: 'default' | 'admin' | 'detail';
    authenticated?: boolean;
};

export default function SkinCard({ skin, mode = 'default', authenticated = false }: Props) {
    const [reason, setReason] = useState('');

    return (
        <div className="flex max-w-md overflow-hidden rounded-lg bg-white shadow-md dark:bg-slate-900">
            <div className="w-1/4 items-center justify-center pt-4 pl-4">
                <img className="mx-auto h-32 w-24 object-contain" src={skin.image_url} alt={skin.name} width={96} height={128} />
            </div>
            <div className="w-3/4 p-4">
                <h1 className="break-all text-2xl font-bold text-slate-900 dark:text-slate-100">
                    {skin.public && skin.show_url ? (
                        <Link href={skin.show_url}>{skin.name}</Link>
                    ) : (
                        skin.name
                    )}
                </h1>
                <p className="mt-2 text-xs text-slate-600 dark:text-slate-200">
                    {skin.is_owner && (
                        <>
                            Public: {skin.public ? 'Yes' : 'No'}
                            <br />
                        </>
                    )}
                    {skin.publisher ? (
                        <>
                            Published by:{' '}
                            <Link className="text-green-800 hover:text-green-600 dark:text-green-500 dark:hover:text-green-300" href={skin.publisher.url}>
                                {skin.publisher.username}
                            </Link>
                            <br />
                        </>
                    ) : (
                        <>
                            Game Jolt ID: {skin.owner_id}
                            <br />
                        </>
                    )}
                    Uploaded: {skin.uploaded_at}
                    <br />
                    File size: {skin.file_size}
                </p>
                <div className="item-center mt-2 flex text-sm text-black dark:text-white">
                    <p>{skin.likes_count} likes</p>
                </div>

                {mode === 'admin' ? (
                    <Form {...destroyUploaded.form(skin.uuid)} className="mt-3 w-full" onSuccess={() => setReason('')}>
                        {({ processing }) => (
                            <>
                                <p className="my-2 m-0 text-xs text-blue-500">Users will be able to see the reason for the deletion!</p>
                                <Input
                                    name="reason"
                                    value={reason}
                                    onChange={(event) => setReason(event.target.value)}
                                    placeholder="Add a legit reason here"
                                    required
                                />
                                <Button type="submit" variant="destructive" size="sm" className="mt-2" disabled={processing}>
                                    Delete
                                </Button>
                            </>
                        )}
                    </Form>
                ) : (
                    <div className="item-center mt-3 flex flex-wrap gap-2">
                        {mode !== 'detail' && skin.public && skin.show_url && (
                            <Link
                                href={show.url(skin.uuid)}
                                className="rounded bg-blue-800 px-2 py-1 text-xs font-bold uppercase text-blue-50"
                            >
                                Show
                            </Link>
                        )}
                        {authenticated && ! skin.is_owner && (
                            <button
                                type="button"
                                className={`rounded px-2 py-1 text-xs font-bold uppercase ${skin.liked ? 'bg-red-800 text-red-50' : 'bg-red-600 text-red-50'}`}
                                onClick={() => router.post(like.url(skin.uuid))}
                            >
                                {skin.liked ? 'Liked' : 'Like'}
                            </button>
                        )}
                        {authenticated && skin.is_owner && (
                            <>
                                <Link
                                    href={edit.url(skin.uuid)}
                                    className="rounded bg-yellow-600 px-2 py-1 text-xs font-bold uppercase text-yellow-50"
                                >
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    className="rounded bg-red-700 px-2 py-1 text-xs font-bold uppercase text-red-50"
                                    onClick={() => {
                                        if (confirm('Delete this skin?')) {
                                            router.delete(destroy.url(skin.uuid));
                                        }
                                    }}
                                >
                                    Delete
                                </button>
                            </>
                        )}
                        {authenticated && (
                            <button
                                type="button"
                                className="rounded bg-slate-800 px-2 py-1 text-xs font-bold uppercase text-slate-50"
                                onClick={() => router.post(apply.url(skin.uuid))}
                            >
                                Apply
                            </button>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
}
