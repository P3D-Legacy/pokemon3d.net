<?php

namespace App\Http\Livewire\Resource;

use App\Models\ResourceUpdate;
use LivewireUI\Modal\ModalComponent;

class UpdateShow extends ModalComponent
{
    public int|ResourceUpdate $update;

    public function mount(int|ResourceUpdate $update)
    {
        $this->update = ResourceUpdate::findOrFail($update);
    }

    public function download()
    {
        $this->update->incrementDownload();
        $mediaItem = $this->update->getFirstMedia('resource_update_file');
        $this->emit('resourceUpdated', $this->update->resource->uuid);
        $this->closeModal();

        return response()->download($mediaItem->getPath(), $mediaItem->name);
    }

    public function render()
    {
        return view('livewire.resource.update-show');
    }
}
