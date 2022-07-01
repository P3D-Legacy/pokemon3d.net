<?php

namespace App\Http\Livewire\Category;

use Illuminate\Support\Collection;
use LivewireUI\Modal\ModalComponent;
use AliBayat\LaravelCategorizable\Category;

class CategoryForm extends ModalComponent
{
    public int|Category $category;
    public Collection $categories;
    public $parent;

    protected array $rules = [
        'category.name' => 'required|min:3|max:255',
    ];

    public function mount(int|Category $category = null)
    {
        $this->category = $category ? Category::find($category) : new Category();
        $this->categories = $category ? Category::whereNot('id', $this->category->id)->get() : Category::all();
    }

    public function save()
    {
        $this->validate();
        $c = $this->category;
        $this->category->save();
        $parent = null;
        if ($this->parent && $c->children()->count() == 0) {
            $parent = Category::findById($this->parent);
        }
        $parent?->appendNode($c);
        $this->closeModal();

        $this->emit('categoryAdded');
    }

    public function render()
    {
        return view('livewire.category.category-form');
    }
}
