<?php

namespace App\View\Components;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TicketHeader extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Setting $setting,
        public string $documentType,
        public string $documentDate,
        public ?string $documentCode = null,
        public ?string $documentNcf = null
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ticket-header');
    }
}
