<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $title, $url, $prev;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $url, $prev)
    {
        $this->title = $title;
        $this->url = $url;
        $this->prev = $prev;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
