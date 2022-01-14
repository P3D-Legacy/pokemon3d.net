<?php

namespace App\Http\Livewire\Resource;

use App\Models\Resource;
use App\Rules\StrNotContain;
use LivewireUI\Modal\ModalComponent;
use AliBayat\LaravelCategorizable\Category;

class ResourceEdit extends ModalComponent
{
    public Resource $resource;
    public int $resource_id;
    public $categories;
    public $name;
    public $breif;
    public $description;
    public $category;

    public function mount($resource_id)
    {
        $this->resource_id = $resource_id;
        $this->resource = Resource::find($this->resource_id)->firstOrFail();
        dd($this->resource);
        $this->categories = Category::all();
        $this->name = $this->resource->name;
        $this->breif = $this->resource->breif;
        $this->description = $this->resource->description;
        $this->category = $this->resource->category_id;
    }

    public function save() {
        $this->validate([
            'name' => [
                'required',
                'string',
                new StrNotContain('official'),
            ],
            'breif' => [
                'required',
                'string',
            ],
            'category' => [
                'required',
                'integer',
            ],
            'description' => [
                'required',
                'string',
            ],
        ]);

        $this->resource->update([
            'name' => $this->name,
            'breif' => $this->breif,
            'description' => $this->description,
        ]);
        $category = Category::find($this->category);
        $this->resource->syncCategories($category);
        
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.resource.resource-edit');
    }
}
