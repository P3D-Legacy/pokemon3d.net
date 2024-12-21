<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\UsersPerDay;
use Laravel\Nova\Dashboard;

class UserInsights extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [new NewUsers, new UsersPerDay];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'user-insights';
    }
}
