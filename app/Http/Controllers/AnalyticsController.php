<?php

namespace App\Http\Controllers;

use App\Helpers\NumberHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Kilobyteno\LaravelPlausible\Plausible;

class AnalyticsController extends Controller
{
    /**
     * Display Plausible analytics for the site.
     */
    public function __invoke(Request $request): Response
    {
        $domain = 'pokemon3d.net';
        $periods = Plausible::getAllowedPeriods();
        $selectedPeriod = $request->string('period')->toString();

        if (! in_array($selectedPeriod, $periods, true)) {
            $selectedPeriod = 'month';
        }

        return Inertia::render('mod/analytics', [
            'domain' => $domain,
            'periods' => $periods,
            'selectedPeriod' => $selectedPeriod,
            'stats' => [
                'visitors' => NumberHelper::nearestK(Plausible::getVisitors($domain, $selectedPeriod)),
                'pageviews' => NumberHelper::nearestK(Plausible::getPageviews($domain, $selectedPeriod)),
                'bounceRate' => Plausible::getBounceRate($domain, $selectedPeriod),
                'visitDuration' => Plausible::getVisitDuration($domain, $selectedPeriod),
                'realtimeVisitors' => Plausible::getRealtimeVisitors($domain),
            ],
        ]);
    }
}
