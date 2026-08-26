<?php

namespace App\View\Components\Home;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Screenshot extends Component
{
    public $title;

    public $path;

    public $author;

    public $active;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $path, $author, $active)
    {
        $this->title = $title;
        $this->path = $path;
        $this->author = $author;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return view('components.home.screenshot');
    }
}
