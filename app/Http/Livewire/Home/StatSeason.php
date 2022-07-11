<?php

namespace App\Http\Livewire\Home;

use App\Helpers\StatsHelper;
use Livewire\Component;

class StatSeason extends Component
{
    public $inGameSeason;

    public function mount()
    {
        $this->inGameSeason = ucfirst(StatsHelper::getInGameSeason());
    }

    public function render()
    {
        return view('livewire.home.stat-season');
    }
}
