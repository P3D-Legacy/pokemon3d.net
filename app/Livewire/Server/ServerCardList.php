<?php

namespace App\Livewire\Server;

use App\Models\Server;
use Livewire\Component;

class ServerCardList extends Component
{
    public $servers;

    public $my_servers;

    public $user;

    protected $listeners = [
        'serverUpdated' => 'update',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function update()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.server.server-card-list');
    }

    private function loadData(): void
    {
        $this->user = auth()->user() ?? null;
        $this->servers = isset($this->user) ? Server::where('user_id', '!=', $this->user->id)
            ->where('active', true)
            ->orderBy('official', 'desc')
            ->orderBy('last_online_at', 'desc')
            ->orderBy('ping', 'asc')
            ->get() : Server::orderBy('official', 'desc')
            ->where('active', true)
            ->orderBy('last_online_at', 'desc')
            ->orderBy('ping', 'asc')
            ->get();
        $this->my_servers = isset($this->user) ? Server::where('user_id', $this->user->id)->get() : collect();
    }
}
