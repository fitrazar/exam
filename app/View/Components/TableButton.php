<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableButton extends Component
{
    public $create, $export, $import;
    /**
     * Create a new component instance.
     */
    public function __construct($create, $export, $import)
    {
        $this->create = $create;
        $this->export = $export;
        $this->import = $import;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-button');
    }
}
