<?php

namespace App\Http\Livewire\Server;

use App\Models\Server;
use App\Rules\IPHostnameARecord;
use App\Rules\StrNotContain;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Redirector;

class ServerEditForm extends Component
{
    public Server $server;

    public string $name;

    public string $host;

    public int $port;

    public string $description;

    public function mount(Server $server): void
    {
        $this->server = $server;
        $this->name = $this->server->name;
        $this->host = $this->server->host;
        $this->port = $this->server->port;
        $this->description = $this->server->description;
    }

    /**
     * Update the server.
     */
    public function save(): Redirector
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->validate([
            'name' => ['required', 'string', new StrNotContain('official')],
            'host' => ['required', new StrNotContain('pokemon3d.net'), new IPHostnameARecord(), 'lowercase'],
            'port' => ['required', 'integer', 'min:10', 'max:99999'],
            'description' => ['nullable', 'string'],
        ]);

        $this->server->update([
            'name' => $this->name,
            'host' => $this->host,
            'port' => $this->port,
            'description' => $this->description,
            'user_id' => auth()->user()->id,
        ]);
        $this->emit('serverUpdated');

        return redirect()->route('server.index');
    }

    public function render(): View
    {
        return view('livewire.server.server-edit-form');
    }
}
