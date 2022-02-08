<?php

namespace App\Http\Livewire\Server;

use App\Models\Server;
use Livewire\Component;

class ServerCardList extends Component
{
    public $servers;

    protected $listeners = [
        "serverUpdated" => "update",
    ];

    public function mount()
    {
        $this->servers = Server::orderBy("official", "desc")
            ->orderBy("last_online_at", "desc")
            ->orderBy("ping", "asc")
            ->get();
    }

    public function update()
    {
        $this->servers = Server::orderBy("official", "desc")
            ->orderBy("last_online_at", "desc")
            ->orderBy("ping", "asc")
            ->get();
    }

    public function render()
    {
        return view("livewire.server.server-card-list");
    }
}
