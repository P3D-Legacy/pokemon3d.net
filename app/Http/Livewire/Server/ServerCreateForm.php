<?php

namespace App\Http\Livewire\Server;

use App\Models\Server;
use Livewire\Component;
use App\Rules\StrNotContain;
use App\Rules\IPHostnameARecord;
use Illuminate\Support\Facades\Artisan;

class ServerCreateForm extends Component
{
    public $name;
    public $host;
    public $port;
    public $description;

    /**
     * Create a server.
     *
     * @return void
     */
    public function save()
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->validate([
            'name' => ['required', 'string', new StrNotContain('official')],
            'host' => ['required', new StrNotContain('pokemon3d.net'), new IPHostnameARecord()],
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
        $this->emit('serverUpdated');
        return redirect()->route('server.index');
    }

    public function render()
    {
        return view('livewire.server.server-create-form');
    }
}
