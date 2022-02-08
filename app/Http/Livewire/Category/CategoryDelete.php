<?php

namespace App\Http\Livewire\Category;

use LivewireUI\Modal\ModalComponent;
use AliBayat\LaravelCategorizable\Category;

class CategoryDelete extends ModalComponent
{
    public $category_id;
    public $category;

    public function mount()
    {
        $this->category = Category::find($this->category_id);
    }

    public function delete()
    {
        $this->category->delete();
        $this->closeModal();
        $this->emit("categoryAdded");
    }

    public function render()
    {
        return view("livewire.category.category-delete");
    }
}
