<?php

namespace App\Helpers;

use App\Enums\ToastMessageEnum;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\RedirectResponse;

if(!function_exists('flashToast')){
    function flashToast(ToastMessageEnum $message):RedirectResponse{
        Inertia::flash('toast', [
            'type' => $message->toastType()->value,
            'message' => $message->message()
        ]);

        return back();
    }
}
