<?php

namespace App\Http\Livewire\Server;

use App\Models\Server;
use App\Rules\IPHostnameARecord;
use App\Rules\StrNotContain;
use Livewire\Component;

class ServerEditForm extends Component
{
    public $server;

    public $name;

    public $host;

    public $port;

    public $description;

    public function mount()
    {
        $this->name = $this->server->name;
        $this->host = $this->server->host;
        $this->port = $this->server->port;
        $this->description = $this->server->description;
    }

    /**
     * Update the server.
     *
     * @return void
     */
    public function save(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->validate([
            'name' => ['required', 'string', new StrNotContain('official')],
            'host' => ['required', new StrNotContain('pokemon3d.net'), new IPHostnameARecord()],
            'port' => ['required', 'integer', 'min:10', 'max:99999'],
            'description' => ['nullable', 'string'],
        ]);

        $server = Server::find($this->server->uuid);

        if (! $server) {
            session()->flash('flash.banner', trans('Server not found.'));
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('server.index');
        }

        $server->update([
            'name' => $this->name,
            'host' => $this->host,
            'port' => $this->port,
            'description' => $this->description,
            'user_id' => auth()->user()->id,
        ]);
        $this->emit('serverUpdated');

        return redirect()->route('server.index');
    }

    public function render()
    {
        return view('livewire.server.server-edit-form');
    }
}
