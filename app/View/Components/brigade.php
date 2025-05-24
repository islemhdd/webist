<?php

namespace App\View\Components;

use App\Models\Officer;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class brigade extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Officer $officer)
    {
        $this->officer = auth()->user()->isOfficer();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.brigade', [
            'officer' => $this->officer,
        ]);
    }
}
