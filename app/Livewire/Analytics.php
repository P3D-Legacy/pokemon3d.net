<?php

namespace App\Livewire;

use App\Helpers\NumberHelper;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Kilobyteno\LaravelPlausible\Plausible;
use Livewire\Component;

class Analytics extends Component
{
    public string $domain = 'pokemon3d.net';

    public string $visitors = '0';

    public string $pageviews = '0';

    public float $bounceRate = 0.0;

    public float $visitDuration = 0.0;

    public int $realtimeVisitors = 0;

    public array $periods = [];

    public string $selectedPeriod = 'month';

    public array $labels = [];

    public array $data = [];

    public function mount()
    {
        $this->visitors = NumberHelper::nearestK(Plausible::getVisitors($this->domain, $this->selectedPeriod));
        $this->pageviews = NumberHelper::nearestK(Plausible::getPageviews($this->domain, $this->selectedPeriod));
        $this->bounceRate = Plausible::getBounceRate($this->domain, $this->selectedPeriod);
        $this->visitDuration = Plausible::getVisitDuration($this->domain, $this->selectedPeriod);
        $this->realtimeVisitors = Plausible::getRealtimeVisitors($this->domain);
        $this->periods = Plausible::getAllowedPeriods();
        $this->getLabels($this->selectedPeriod);
    }

    public function changePeriod(string $period)
    {
        $this->selectedPeriod = $period;
        $this->getLabels($this->selectedPeriod);
        $this->visitors = NumberHelper::nearestK(Plausible::getVisitors($this->domain, $this->selectedPeriod));
        $this->pageviews = NumberHelper::nearestK(Plausible::getPageviews($this->domain, $this->selectedPeriod));
        $this->bounceRate = Plausible::getBounceRate($this->domain, $this->selectedPeriod);
        $this->visitDuration = Plausible::getVisitDuration($this->domain, $this->selectedPeriod);
    }

    private function getLabels($period)
    {
        $this->labels = [];
        $start = Carbon::now();
        $end = Carbon::now();
        $interval = CarbonInterval::day();
        switch ($period) {
            case 'day':
                $start = Carbon::now()->subDay();
                $interval = CarbonInterval::hours(24);
                break;
            case '7d':
                $start->subDays(7);
                break;
            case '30d':
                $start->subDays(30);
                break;
            case 'month':
                $start = Carbon::parse(Carbon::now())->startOfMonth();
                $end = Carbon::parse(Carbon::now())->endOfMonth();
                break;
            case '6mo':
                $start = Carbon::parse(Carbon::now())->subMonths(6);
                $end = Carbon::parse(Carbon::now())->endOfMonth();
                $interval = CarbonInterval::month();
                break;
            case '12mo':
                $start->subMonths(12);
                $interval = CarbonInterval::month();
                break;
        }
        $date_period = CarbonPeriod::create($start, $end)->interval($interval);
        foreach ($date_period as $date) {
            $this->labels[] = $date->isoFormat('L');
        }
        $this->dispatch('contentChanged');
    }

    public function render()
    {
        return view('livewire.analytics');
    }
}
