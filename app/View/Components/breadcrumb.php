<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class breadcrumb extends Component
{
    public $breadcrumbs;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return view('components.breadcrumb', [
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }
}
