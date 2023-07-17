<?php

namespace App\Http\Livewire\Resource;

use App\Models\GameVersion;
use App\Models\Resource;
use App\Models\ResourceUpdate;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
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
        'file' => ['required', 'file', 'mimes:zip', 'max:100000'], //100mb
        'gameversion' => ['required'],
    ];

    public function mount($resource_uuid)
    {
        $this->resource = Resource::where('uuid', $resource_uuid)->first();
        $this->gameversions = GameVersion::orderBy('release_date', 'desc')->get();
    }

    public function save()
    {
        $this->validate();

        $this->resourceUpdate = ResourceUpdate::create([
            'title' => $this->version,
            'description' => $this->description,
            'resource_id' => $this->resource->id,
            'game_version_id' => $this->gameversion,
        ]);

        $file_name = Str::slug($this->resource->name).'-'.$this->resourceUpdate->title.'.'.$this->file->extension();

        $this->resourceUpdate->clearMediaCollection('resource_update_file');
        $this->resourceUpdate
            ->addMedia($this->file->getRealPath())
            ->usingName($file_name)
            ->toMediaCollection('resource_update_file');

        $this->emit('resourceUpdated', $this->resource->uuid);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.resource.update-create');
    }
}
