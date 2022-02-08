<?php

namespace App\Http\Livewire\Category;

use LivewireUI\Modal\ModalComponent;
use AliBayat\LaravelCategorizable\Category;

class CategoryCreate extends ModalComponent
{
    public $name;

    public function save()
    {
        $this->validate([
            "name" => "required|min:3|max:255",
        ]);

        $isFound = Category::where("name", $this->name)->first();

        if ($isFound) {
            $this->addError("name", "Category already exists.");
        } else {
            Category::create([
                "name" => $this->name,
            ]);
            $this->closeModal();
        }

        $this->emit("categoryAdded");
    }

    public function render()
    {
        return view("livewire.category.category-create");
    }
}
