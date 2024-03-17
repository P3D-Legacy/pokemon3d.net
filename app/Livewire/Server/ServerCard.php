<?php

namespace App\Livewire\Server;

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
        Artisan::call('server:ping '.$this->server->uuid.' true');
        $this->server->active = true;
        $this->server->save();
        $this->dispatch('serverUpdated');
    }

    public function destroy()
    {
        $this->server->delete();
        $this->dispatch('serverUpdated');
    }

    public function render()
    {
        return view('livewire.server.server-card');
    }
}
