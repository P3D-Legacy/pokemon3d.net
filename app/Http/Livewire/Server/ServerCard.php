<?php

namespace App\Http\Livewire\Server;

use App\Models\Server;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class ServerCard extends Component
{
    public Server $server;

    public function mount(Server $server)
    {
        $this->server = $server;
    }

    public function reactivate()
    {
        Artisan::call('ping:server ' . $this->server->uuid . ' true');
        $this->server->active = true;
        $this->server->save();
        $this->emit('serverUpdated');
    }

    public function destroy()
    {
        $this->server->delete();
        $this->emit('serverUpdated');
    }

    public function render()
    {
        return view('livewire.server.server-card');
    }
}
