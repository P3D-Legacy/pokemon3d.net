<?php

namespace App\Http\Livewire;

use Livewire\Component;
use AliBayat\LaravelCategorizable\Category;

class IndexCategory extends Component
{
    public $categories;

    protected $listeners = [
        'categoryAdded' => 'mount',
    ];

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.index-category');
    }
}
