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
        $this->resource = Resource::with('categories')
            ->find($resource)
            ->firstOrFail();
    }

    public function update(int|Resource $resource)
    {
        $this->resource = Resource::with('categories')
            ->find($resource)
            ->firstOrFail();
    }

    public function download()
    {
        $update = $this->resource->updates->first();
        $update->incrementDownload();
        $mediaItem = $update->getFirstMedia('resource_update_file');
        $this->emit('resourceUpdated', $this->resource->id);
        return response()->download(
            $mediaItem->getPath(),
            $mediaItem->file_name
        );
    }

    public function render()
    {
        return view('livewire.resource.resource-show');
    }
}
