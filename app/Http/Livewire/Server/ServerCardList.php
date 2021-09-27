<?php

namespace App\Http\Livewire\Server;

use App\Models\Server;
use Livewire\Component;

class ServerCardList extends Component
{
    public $servers;

    protected $listeners = [
        'serverUpdated' => '$refresh',
    ];

    public function mount() {
        $this->servers = Server::orderBy('official', 'desc')->orderBy('last_online_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.server.server-card-list');
    }
}
