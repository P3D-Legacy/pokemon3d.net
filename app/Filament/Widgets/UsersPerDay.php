<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UsersPerDay extends ChartWidget
{
    protected static ?string $heading = 'Users per day';

    protected static ?int $sort = 2;

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            '30' => 'Last 30 days',
            '60' => 'Last 60 days',
            '90' => 'Last 90 days',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 30);
        $from = now()->subDays($days - 1)->startOfDay();

        $counts = User::query()
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as aggregate')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('aggregate', 'date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $from->copy()->addDays($i);
            $key = $date->toDateString();
            $labels[] = $date->format('M j');
            $data[] = (int) ($counts[$key] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
