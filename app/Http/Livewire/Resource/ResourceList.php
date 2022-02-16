<?php

namespace App\Http\Livewire\Resource;

use Livewire\Component;
use App\Models\Resource;
use AliBayat\LaravelCategorizable\Category;

class ResourceList extends Component
{
    public $resources;

    protected $listeners = [
        'resourceUpdated' => 'update',
    ];

    public function mount()
    {
        if (request()->is('resource/category/*')) {
            $this->resources = Category::findByName(request()->segment(3))
                ->entries(Resource::class)->get();
        } else {
            $this->resources = Resource::all();
        }
    }

    public function update()
    {
        if (request()->is('resource/category/*')) {
            $this->resources = Category::findByName(request()->segment(3))
                ->entries(Resource::class)->get();
        } else {
            $this->resources = Resource::all();
        }
    }

    public function render()
    {
        return view('livewire.resource.resource-list');
    }
}
