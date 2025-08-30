<?php

namespace App\Livewire\Resource;

use AliBayat\LaravelCategorizable\Category;
use App\Models\Resource;
use Livewire\Component;

class ResourceForm extends Component
{
    public $name;

    public $brief;

    public $description;

    public $categories;

    public $category;

    public $isSubmitting = false;

    public $resourceId = null;

    protected array $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'required|min:3|max:5120',
        'brief' => 'required|min:3|max:255',
        'category' => 'required|exists:categories,id',
    ];

    public function mount(int|Resource|null $resource = null)
    {
        if ($resource) {
            // Handle both Resource model instances and IDs
            if (is_numeric($resource)) {
                $resource = Resource::find($resource);
            }

            $this->resourceId = $resource->id;
            $this->name = $resource->name;
            $this->brief = $resource->brief;
            $this->description = $resource->description;
            $this->category = $resource->categories->first()->id ?? 0;
        } else {
            $this->resourceId = null;
            $this->name = '';
            $this->brief = '';
            $this->description = '';
            $this->category = 0;
        }

        $this->categories = Category::all();
    }

    public function save()
    {
        // Prevent duplicate submissions
        if ($this->isSubmitting) {
            return;
        }

        $this->isSubmitting = true;

        try {
            $this->validate();

            if ($this->resourceId) {
                // Update existing resource
                $resource = Resource::findOrFail($this->resourceId);

                // Check ownership
                if ($resource->user_id !== auth()->id()) {
                    abort(403);
                }

                $resource->update([
                    'name' => $this->name,
                    'brief' => $this->brief,
                    'description' => $this->description,
                ]);

                $successMessage = __(':item updated successfully.', ['item' => __('Resource')]);
                $redirectRoute = route('resource.uuid', $resource->uuid);
            } else {
                // Create new resource
                $resource = Resource::create([
                    'name' => $this->name,
                    'brief' => $this->brief,
                    'description' => $this->description,
                    'user_id' => auth()->id(),
                ]);

                $successMessage = __(':item created successfully.', ['item' => __('Resource')]);
                $redirectRoute = route('resource.index');
            }

            $category = Category::find($this->category);
            if ($category) {
                $resource->syncCategories($category);
            }

            session()->flash('flash.bannerStyle', 'success');
            session()->flash('flash.banner', $successMessage);

            return redirect($redirectRoute);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Reset submission flag on validation error
            $this->isSubmitting = false;
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.resource.resource-form');
    }
}
