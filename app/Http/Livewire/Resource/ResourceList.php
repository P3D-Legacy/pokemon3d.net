<?php

namespace App\Http\Livewire\Resource;

use Livewire\Component;
use App\Models\Resource;

class ResourceList extends Component
{
    public $resources;

    protected $listeners = [
        'resourceUpdated' => 'update',
    ];

    public function mount()
    {
        $this->resources = Resource::orderBy('created_at', 'desc')->get();
    }

    public function update()
    {
        $this->resources = Resource::orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.resource.resource-list');
    }
}
