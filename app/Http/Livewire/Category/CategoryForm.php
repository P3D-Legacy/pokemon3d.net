<?php

namespace App\Http\Livewire\Category;

use LivewireUI\Modal\ModalComponent;
use AliBayat\LaravelCategorizable\Category;

class CategoryForm extends ModalComponent
{

    public int|Category $category;

    protected array $rules = [
        'category.name' => 'required|min:3|max:255|unique:categories,name',
    ];

    public function mount(int|Category $category = null)
    {
        $this->category = $category ? Category::find($category) : new Category;
    }

    public function save()
    {
        $this->validate();

        $this->category->save();
        $this->closeModal();

        $this->emit('categoryAdded');
    }

    public function render()
    {
        return view('livewire.category.category-form');
    }
}
