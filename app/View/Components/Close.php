<?php

namespace App\View\Components;

use App\Models\CashRegister;
use App\Models\Setting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Close extends Component
{

    public array $dataSummary;
    public string $code;

    public function __construct(
        public Setting $setting,
        public CashRegister $cashRegister
    ) {
        $this->dataSummary = $this->cashRegister->summary;

        $this->code = Str::substr($this->cashRegister->uuid, -6);
    }

    public function render(): View|Closure|string
    {
        return view('pdfs.cashier.close',[
            'dataSummary' => $this->dataSummary,
            'code' => $this->code,
        ]);
    }
}
