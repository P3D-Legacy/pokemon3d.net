<?php

namespace App\Http\Livewire\Resource;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use App\Models\Resource;
use AliBayat\LaravelCategorizable\Category;
use Livewire\WithPagination;

class ResourceList extends Component
{
    use WithPagination;

    protected $listeners = [
        'resourceUpdated' => 'update',
    ];

    public function render()
    {
        if (request()->is('resource/category/*')) {
            $perPage = 15; // Default for pagination
            $filtered = Resource::all()->filter(function ($resource) {
                return $resource->hasCategory(Category::where('slug', request()->segment(3))->first());
            });
            $resources = new LengthAwarePaginator(
                $filtered->slice(LengthAwarePaginator::resolveCurrentPage() * $perPage - $perPage, $perPage)->all(),
                count($filtered),
                $perPage,
                null,
                ['path' => '']
            );
        } else {
            $resources = Resource::paginate();
        }
        return view('livewire.resource.resource-list', [
            'resources' => $resources,
        ]);
    }
}
