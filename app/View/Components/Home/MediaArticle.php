<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class MediaArticle extends Component
{
    public $title;
    public $url;
    public $author;
    public $date;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $url, $author, $date)
    {
        $this->title = $title;
        $this->url = $url;
        $this->author = $author;
        $this->date = $date;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.media-article');
    }
}
