<?php

namespace App\Livewire\Resource;

use App\Models\ResourceUpdate;
use LivewireUI\Modal\ModalComponent;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class UpdateShow extends ModalComponent
{
    public int|ResourceUpdate $update;

    public function mount(int|ResourceUpdate $update)
    {
        $this->update = ResourceUpdate::findOrFail($update);
    }

    public function download()
    {
        $mediaItem = $this->update->getFirstMedia('resource_update_file');
        if (! $mediaItem) {
            session()->flash('flash.banner', trans('File not found on server!'));
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('resource.uuid', $this->update->resource->uuid);
        }
        $this->update->incrementDownload();
        $this->dispatch('resourceUpdated', $this->update->resource->uuid);
        $this->closeModal();

        try {
            return response()->download($mediaItem->getPath(), $mediaItem->name);
        } catch (FileNotFoundException) {
            session()->flash('flash.banner', trans('File not found on server!'));
            session()->flash('flash.bannerStyle', 'danger');

            return redirect()->route('resource.uuid', $this->update->resource->uuid);
        }
    }

    public function render()
    {
        return view('livewire.resource.update-show');
    }
}
