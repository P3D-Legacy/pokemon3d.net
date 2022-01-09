<?php

namespace App\Http\Livewire\Resource;

use Livewire\Component;
use App\Models\Resource;
use App\Rules\StrNotContain;

class ResourceCreateForm extends Component
{
    public $name;
    public $description;

    /**
     * Create a resource.
     *
     * @return void
     */
    public function save()
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->validate([
            'name' => [
                'required',
                'string',
                new StrNotContain('official'),
            ],
            'description' => [
                'string',
            ],
        ]);

        $resource = Resource::create([
            'name' => $this->name,
            'description' => $this->description,
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('resource.show', ['resource' => $resource]);
        
    }

    public function render()
    {
        return view('livewire.resource.resource-create-form');
    }
}
