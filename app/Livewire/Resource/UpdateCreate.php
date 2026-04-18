<?php

namespace App\Livewire\Resource;

use App\Models\GameVersion;
use App\Models\Resource;
use App\Models\ResourceUpdate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateCreate extends Component
{
    use WithFileUploads;

    public ResourceUpdate $resourceUpdate;

    public int|Resource $resource;

    public $file;

    public $version;

    public $description;

    public $gameversion;

    public $gameversions;

    public $isSubmitting = false;

    protected array $rules = [
        'version' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string', 'max:5120'],
        'file' => ['required', 'file', 'mimes:zip', 'max:100000'], // 100mb
        'gameversion' => ['required'],
    ];

    public function mount($resource_uuid)
    {
        $this->resource = Resource::where('uuid', $resource_uuid)->firstOrFail();

        // Check ownership
        if ($this->resource->user_id !== auth()->id()) {
            abort(403);
        }

        $this->gameversions = GameVersion::orderBy('release_date', 'desc')->get();
    }

    public function save()
    {
        // Prevent duplicate submissions
        if ($this->isSubmitting) {
            return;
        }

        $this->isSubmitting = true;

        try {
            // Double check ownership
            if ($this->resource->user_id !== auth()->id()) {
                abort(403);
            }

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

            session()->flash('flash.bannerStyle', 'success');
            session()->flash('flash.banner', __('Update posted successfully!'));

            return redirect()->route('resource.uuid', $this->resource->uuid);
        } catch (ValidationException $e) {
            // Reset submission flag on validation error
            $this->isSubmitting = false;
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.resource.update-create');
    }
}
