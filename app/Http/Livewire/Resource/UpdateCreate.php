<?php

namespace App\Http\Livewire\Resource;

use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;

class UpdateCreate extends ModalComponent
{
    use WithFileUploads;

    public function render()
    {
        return view('livewire.resource.update-create');
    }
}
