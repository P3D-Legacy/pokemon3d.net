<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class FooterLink extends Component
{
    public $title;
    public $url;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $url)
    {
        $this->title = $title;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view("components.home.footer-link");
    }
}
