<?php

namespace App\Livewire\Resource;

use App\Models\Resource;
use LivewireUI\Modal\ModalComponent;

class ResourceDelete extends ModalComponent
{
    public int|Resource $resource;

    public function mount()
    {
        $this->resource = Resource::find($this->resource);
    }

    public function delete()
    {
        $this->resource->delete();
        $this->closeModal();
        $this->dispatch('resourceAdded');
        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __(':item deleted successfully.', ['item' => __('Resource')]));

        return redirect()->route('resource.index');
    }

    public function render()
    {
        return view('livewire.resource.resource-delete');
    }
}
