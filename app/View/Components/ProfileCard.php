<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $intro,
        public string $institute,
        public string $email,
        public string $phone,
        public string $address
    )
    {
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile-card');
    }
}
