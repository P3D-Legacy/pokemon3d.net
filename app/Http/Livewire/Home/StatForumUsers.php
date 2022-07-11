<?php

namespace App\Http\Livewire\Home;

use App\Helpers\NumberHelper;
use App\Helpers\XenForoHelper;
use Livewire\Component;

class StatForumUsers extends Component
{
    public $forumUsers;

    public function mount()
    {
        $this->forumUsers = NumberHelper::nearestK(XenForoHelper::getUserCount());
    }

    public function render()
    {
        return view('livewire.home.stat-forum-users');
    }
}
