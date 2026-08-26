import { Head, router } from '@inertiajs/react';

import { useTranslations } from '@/hooks/use-translations';

type Props = {
    domain: string;
    periods: string[];
    selectedPeriod: string;
    stats: {
        visitors: string;
        pageviews: string;
        bounceRate: number;
        visitDuration: number;
        realtimeVisitors: number;
    };
};

export default function Analytics({ domain, periods, selectedPeriod, stats }: Props) {
    const { t } = useTranslations();

    return (
        <>
            <Head title={t('Analytics')} />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div className="mb-6">
                    <h1 className="text-xl font-semibold text-slate-800 dark:text-slate-200">
                        {t('Domain')} — {domain}
                    </h1>
                    <p className="mt-1 text-sm text-slate-500">
                        <span className="mr-1 inline-block size-2 rounded-full bg-green-500" />
                        {stats.realtimeVisitors} {t('current visitors')}
                    </p>
                </div>

                <div className="mb-6 flex flex-wrap gap-2">
                    {periods.map((period) => (
                        <button
                            key={period}
                            type="button"
                            onClick={() => router.get('/mod/analytics', { period }, { preserveState: true })}
                            className={`rounded-md px-4 py-2 text-sm ${
                                period === selectedPeriod
                                    ? 'bg-slate-800 text-white dark:bg-slate-100 dark:text-slate-900'
                                    : 'bg-white text-slate-600 hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-300'
                            }`}
                        >
                            {period}
                        </button>
                    ))}
                </div>

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <StatCard label={t('Visitors')} value={stats.visitors} />
                    <StatCard label={t('Pageviews')} value={stats.pageviews} />
                    <StatCard label={t('Bounce Rate')} value={`${stats.bounceRate}%`} />
                    <StatCard label={t('Visit Duration')} value={`${stats.visitDuration}s`} />
                </div>
            </div>
        </>
    );
}

function StatCard({ label, value }: { label: string; value: string | number }) {
    return (
        <div className="rounded-lg border border-slate-300 bg-white px-4 py-6 text-center dark:border-slate-700 dark:bg-black">
            <p className="text-3xl font-semibold text-slate-800 dark:text-slate-100">{value}</p>
            <p className="text-lg text-slate-500">{label}</p>
        </div>
    );
}
