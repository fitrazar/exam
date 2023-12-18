<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableBody extends Component
{
    public $items, $key, $url, $route, $relation;
    /**
     * Create a new component instance.
     */
    public function __construct($items, $key, $url, $route, $relation)
    {
        $this->items = $items;
        $this->key = $key;
        $this->url = $url;
        $this->route = $route;
        $this->relation = $relation;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-body');
    }
}
