<?php

namespace App\Http\Livewire\Resource;

use App\Models\GameVersion;
use App\Models\Resource;
use Livewire\WithFileUploads;
use App\Models\ResourceUpdate;
use LivewireUI\Modal\ModalComponent;

class UpdateCreate extends ModalComponent
{
    use WithFileUploads;

    public ResourceUpdate $resourceUpdate;
    public int|Resource $resource;
    public $file;
    public $version;
    public $description;
    public $gameversion;
    public $gameversions;

    protected array $rules = [
        'version' => ['required', 'string'],
        'description' => ['required', 'string'],
        'file' => ['required', 'file', 'mimes:zip'],
        'gameversion' => ['required'],
    ];

    public function mount(int|Resource $resource)
    {
        $this->resource = $resource;
        $this->gameversions = GameVersion::all();
    }

    public function save()
    {
        $this->validate();

        $this->resourceUpdate = ResourceUpdate::create([
            'title' => $this->version,
            'description' => $this->description,
            'resource_id' => $this->resource,
            'game_version_id' => $this->gameversion,
        ]);

        $this->resourceUpdate->clearMediaCollection('resource_update_file');
        $this->resourceUpdate
            ->addMedia($this->file->getRealPath())
            ->toMediaCollection('resource_update_file');

        $this->emit('resourceUpdated', $this->resource);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.resource.update-create');
    }
}
