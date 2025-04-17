<?php

namespace App\Livewire\Home;

use App\Helpers\NumberHelper;
use App\Models\User;
use Livewire\Component;

class StatUsers extends Component
{
    public $userCount;

    public function loadData()
    {
        $this->userCount = NumberHelper::nearestK(User::all()->count());
    }

    public function render()
    {
        return view('livewire.home.stat-users');
    }
}
