<?php

namespace App\Livewire\Server;

use App\Models\Server;
use App\Rules\IPHostnameARecord;
use App\Rules\StrNotContain;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Redirector;

class ServerCreateForm extends Component
{
    public string $name;

    public string $host;

    public int $port;

    public string $description;

    /**
     * Create a server.
     */
    public function save(): Redirector
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->validate([
            'name' => ['required', 'string', new StrNotContain('official'), 'ascii'],
            'host' => ['required', new StrNotContain('pokemon3d.net'), new IPHostnameARecord, 'lowercase'],
            'port' => ['required', 'integer', 'min:10', 'max:99999'],
            'description' => ['nullable', 'string'],
        ]);

        Server::create([
            'name' => $this->name,
            'host' => $this->host,
            'port' => $this->port,
            'description' => $this->description,
            'user_id' => auth()->user()->id,
        ]);
        $this->dispatch('serverUpdated');

        return redirect()->route('server.index');
    }

    public function render(): View
    {
        return view('livewire.server.server-create-form');
    }
}
