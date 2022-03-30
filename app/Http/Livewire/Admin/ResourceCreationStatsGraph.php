<?php

namespace App\Http\Livewire\Admin;

use App\Stats\ResourceCreationStats;
use Livewire\Component;

class ResourceCreationStatsGraph extends Component
{
    public $stats;
    public $labels;
    public $values;

    public function mount()
    {
        $this->stats = ResourceCreationStats::query()
            ->start(now()->subDays(10))
            ->end(now()->subSecond())
            ->groupByDay()
            ->get();
        $this->labels = $this->stats->pluck('start')->toArray();
        $this->values = $this->stats->pluck('value')->toArray();
    }

    public function render()
    {
        return view('livewire.admin.resource-creation-stats-graph');
    }
}
