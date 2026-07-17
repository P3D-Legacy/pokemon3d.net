import { Link, usePage } from '@inertiajs/react';

import { contact, discord, github, legal } from '@/routes';
import { index as blogIndex } from '@/routes/blog';
import { show as policyShow } from '@/routes/policy';
import { show as termsShow } from '@/routes/terms';
import type { SharedPageProps } from '@/types';

export default function HomeFooter() {
    const { appName } = usePage<SharedPageProps>().props;

    return (
        <footer className="bg-white dark:bg-slate-800">
            <div className="container mx-auto mt-8 px-8">
                <div className="flex w-full flex-col py-6 md:flex-row">
                    <div className="mb-6 flex-2 px-3 text-sm text-slate-600 dark:text-slate-200">
                        <img src="/img/pokemon3d_logo.png" alt={appName} className="max-w-xs" />
                        <p className="mt-3">
                            {appName} is not affiliated with Nintendo, Creatures Inc. or GAME FREAK Inc.
                        </p>
                        <p className="mt-3">
                            pokemon3d.net is owned and operated by{' '}
                            <a href="https://kilobyte.no/" className="text-green-800 no-underline hover:underline">
                                Kilobyte AS
                            </a>
                        </p>
                    </div>
                    <div className="flex-1 px-3">
                        <p className="font-extrabold text-slate-500 uppercase dark:text-slate-200 md:mb-6">Legal</p>
                        <ul className="mb-6 space-y-2">
                            <li>
                                <Link href={termsShow()} className="text-slate-600 hover:underline dark:text-slate-300">
                                    Terms and Conditions
                                </Link>
                            </li>
                            <li>
                                <Link href={policyShow()} className="text-slate-600 hover:underline dark:text-slate-300">
                                    Privacy Policy
                                </Link>
                            </li>
                            <li>
                                <Link href={legal()} className="text-slate-600 hover:underline dark:text-slate-300">
                                    Legal
                                </Link>
                            </li>
                            <li>
                                <Link href={contact()} className="text-slate-600 hover:underline dark:text-slate-300">
                                    Contact
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div className="flex-1 px-3">
                        <p className="font-extrabold text-slate-500 uppercase dark:text-slate-200 md:mb-6">Social</p>
                        <ul className="mb-6 space-y-2">
                            <li>
                                <Link href={discord()} className="text-slate-600 hover:underline dark:text-slate-300">
                                    Discord
                                </Link>
                            </li>
                            <li>
                                <Link href={github()} className="text-slate-600 hover:underline dark:text-slate-300">
                                    GitHub
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div className="flex-1 px-3">
                        <p className="font-extrabold text-slate-500 uppercase dark:text-slate-200 md:mb-6">{appName}</p>
                        <ul className="mb-6 space-y-2">
                            <li>
                                <Link href={blogIndex()} className="text-slate-600 hover:underline dark:text-slate-300">
                                    Official Blog
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    );
}
