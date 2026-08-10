<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewUsers extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $last30Days = User::query()->where('created_at', '>=', now()->subDays(30))->count();
        $today = User::query()->whereDate('created_at', today())->count();
        $monthToDate = User::query()->where('created_at', '>=', now()->startOfMonth())->count();

        return [
            Stat::make('New users (30 days)', $last30Days)
                ->description('Registered in the last 30 days'),
            Stat::make('New users (today)', $today)
                ->description('Registered today'),
            Stat::make('New users (MTD)', $monthToDate)
                ->description('Registered this month'),
        ];
    }
}
