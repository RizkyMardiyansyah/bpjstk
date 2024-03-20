<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardSummary extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.card-summary');
        return view('components.card-summary-stats');
        return view('components.card-summary-stats2');
        return view('components.card-summary-stats3');
    }
}
