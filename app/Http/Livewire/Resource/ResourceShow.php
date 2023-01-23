<?php

namespace App\Http\Livewire\Resource;

use App\Models\Resource;
use Livewire\Component;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ResourceShow extends Component
{
    public Resource $resource;

    protected $listeners = [
        'resourceUpdated' => 'update',
    ];

    public function mount($uuid)
    {
        $this->resource = Resource::with('categories')
            ->where('uuid', $uuid)
            ->firstOrFail();
        views($this->resource)
            ->cooldown(60)
            ->record();
    }

    public function update($uuid)
    {
        $this->resource = Resource::with('categories')
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function download()
    {
        $update = $this->resource->updates->first();
        $update->incrementDownload();
        $mediaItem = $update->getFirstMedia('resource_update_file');

        try {
            return response()->download($mediaItem->getPath(), $mediaItem->file_name);
        } catch (FileNotFoundException) {
            session()->flash('flash.banner', trans('File not found on server!'));
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('resource.uuid', $this->resource->uuid);
        }
    }

    public function render()
    {
        return view('livewire.resource.resource-show');
    }
}
