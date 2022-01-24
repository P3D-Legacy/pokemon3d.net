<?php

namespace App\Http\Livewire\Resource;

use Livewire\Component;
use App\Models\Resource;

class ResourceShow extends Component
{
    public int|Resource $resource;

    protected $listeners = [
        'resourceUpdated' => 'update',
    ];

    public function mount(int|Resource $resource)
    {
        $this->resource = Resource::with('categories')->find($resource)->firstOrFail();
    }

    public function update(int|Resource $resource)
    {
        $this->resource = Resource::with('categories')->find($resource)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.resource.resource-show');
    }
}
