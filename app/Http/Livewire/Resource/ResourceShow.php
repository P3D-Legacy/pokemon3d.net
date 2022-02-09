<?php

namespace App\Http\Livewire\Resource;

use Livewire\Component;
use App\Models\Resource;

class ResourceShow extends Component
{
    public Resource $resource;

    protected $listeners = [
        'resourceUpdated' => 'update',
    ];

    public function mount($uuid)
    {
        $this->resource = Resource::with('categories')->where('uuid', $uuid)->firstOrFail();
        views($this->resource)
            ->cooldown(60)
            ->record();
    }

    public function update($uuid)
    {
        $this->resource = Resource::with('categories')->where('uuid', $uuid)->firstOrFail();
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
