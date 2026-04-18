<?php

namespace App\Livewire\Resource;

use App\Models\Resource;
use Livewire\Component;

class ResourceDelete extends Component
{
    public Resource $resource;

    public function mount(Resource $resource)
    {
        $this->resource = $resource;

        // Check ownership
        if ($resource->user_id !== auth()->id()) {
            abort(403);
        }
    }

    public function delete()
    {
        // Double check ownership before deletion
        if ($this->resource->user_id !== auth()->id()) {
            abort(403);
        }

        $this->resource->delete();

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __(':item deleted successfully.', ['item' => __('Resource')]));

        return redirect()->route('resource.index');
    }

    public function render()
    {
        return view('livewire.resource.resource-delete');
    }
}
