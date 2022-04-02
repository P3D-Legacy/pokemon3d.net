<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ReviewStars extends Component
{
    public $stars;
    public $size;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($stars, $size)
    {
        $this->stars = $stars;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.review-stars');
    }
}
