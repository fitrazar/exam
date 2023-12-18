<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableHeading extends Component
{
    public $thCol;
    /**
     * Create a new component instance.
     */
    public function __construct($th)
    {
        $this->thCol = $this->explodeCol($th);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-heading');
    }

    public function explodeCol($th)
    {
        return explode('|', $th);
    }
}
