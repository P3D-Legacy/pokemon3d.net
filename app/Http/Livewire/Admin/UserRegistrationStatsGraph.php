<?php

namespace App\Http\Livewire\Admin;

use App\Stats\UserRegistrationStats;
use Livewire\Component;

class UserRegistrationStatsGraph extends Component
{
    public $stats;

    public $labels;

    public $values;

    public function mount()
    {
        //UserRegistrationStats::set(random_int(1, 1000), now()->subDays(10));
        $this->stats = UserRegistrationStats::query()
            ->start(now()->subDays(10))
            ->end(now()->subSecond())
            ->groupByDay()
            ->get();
        $this->labels = $this->stats->pluck('start')->toArray();
        $this->values = $this->stats->pluck('value')->toArray();
        //dd($this->stats->pluck('value')->toArray());
    }

    public function render()
    {
        return view('livewire.admin.user-registration-stats-graph');
    }
}
