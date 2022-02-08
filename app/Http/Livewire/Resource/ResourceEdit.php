<?php

namespace App\Http\Livewire\Resource;

use App\Models\Resource;
use App\Rules\StrNotContain;
use LivewireUI\Modal\ModalComponent;
use AliBayat\LaravelCategorizable\Category;

class ResourceEdit extends ModalComponent
{
    public int|Resource $resource;
    public $categories;
    public $name;
    public $breif;
    public $description;
    public $category;

    public function mount(Resource $resource)
    {
        $this->resource = $resource;
        $this->name = $this->resource->name;
        $this->breif = $this->resource->breif;
        $this->description = $this->resource->description;
        $this->category = $this->resource->categories->first()->id;
        $this->category_id = $this->resource->categories->first()->id;
        $this->categories = Category::all();
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', new StrNotContain('official')],
            'breif' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        $this->resource->update([
            'name' => $this->name,
            'breif' => $this->breif,
            'description' => $this->description,
        ]);
        $category = Category::find($this->category);
        $this->resource->syncCategories($category);

        $this->emit('resourceUpdated', $this->resource->id);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.resource.resource-edit');
    }
}
