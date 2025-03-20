<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Base extends Component
{
    /**
     * Create a new component instance.
     */
    public string $title;
    public $id = null;
    public $css = "";


    public function __construct(string $title)
    {
        $this->title = $title;

        if (Auth::guard('officer')->user()) {
            $this->id = Auth::guard('officer')->user()->id;
        } elseif (Auth::guard('student')->user()) {
            $this->id = Auth::guard('student')->user()->id;
        } else {
            $this->id = null; // Optional: handle not logged-in case
        }
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.base');
    }
}
