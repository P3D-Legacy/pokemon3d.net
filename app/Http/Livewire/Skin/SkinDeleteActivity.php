<?php

namespace App\Http\Livewire\Skin;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class SkinDeleteActivity extends Component
{
    public Collection $activity;

    public function mount()
    {
        $this->activity = collect();
    }

    public function loadActivity()
    {
        $this->activity = Activity::where('description', 'deleted')
            ->where('properties', 'LIKE', '%' . Auth::user()->gamejolt->id . '.png%')
            ->orWhere('properties', 'LIKE', '%gjid":' . Auth::user()->gamejolt->id . ',"reason"%')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.skin.skin-delete-activity');
    }
}
