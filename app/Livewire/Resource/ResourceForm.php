<?php

namespace App\Livewire\Resource;

use AliBayat\LaravelCategorizable\Category;
use App\Models\Resource;
use LivewireUI\Modal\ModalComponent;

class ResourceForm extends ModalComponent
{
    public int|Resource $resource;

    public $categories;

    public $category;

    protected array $rules = [
        'resource.name' => 'required|min:3|max:255',
        'resource.description' => 'required|min:3|max:5120',
        'resource.brief' => 'required|min:3|max:255',
        'category' => 'required|exists:categories,id',
    ];

    public function mount(int|Resource|null $resource = null)
    {
        $this->resource = $resource ? Resource::find($resource) : new Resource;
        $this->category = $this->resource->categories->first()->id ?? 0;
        $this->categories = Category::all();
    }

    public function save()
    {
        $this->validate();

        $this->resource->user_id = auth()->id();
        $this->resource->save();

        $category = Category::find($this->category);
        if ($category) {
            $this->resource->syncCategories($category);
        }

        $this->dispatch('resourceUpdated', $this->resource->uuid);
        //$this->dispatch('openModal', component: component: 'resource.update-create', arguments: json_encode(['resource_uuid' => $this->resource->uuid]));
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.resource.resource-form');
    }
}
