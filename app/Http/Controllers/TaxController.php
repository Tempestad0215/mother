<?php

namespace App\Http\Controllers;

use App\Enums\ToastMessageEnum;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use function App\Helpers\flashToast;

class TaxController extends Controller
{
    public function index()
    {

        return Inertia::render('Setting/Tax/Register', [
            'taxes' => Tax::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:2','max:50','unique:taxes,name'],
            'description' => ['nullable','string','max:150'],
            'rate' => ['required','numeric','min:0'],
        ]);

        Tax::create($validated);

        return flashToast(ToastMessageEnum::CREATED);
    }

    public function update(Request $request, Tax $tax)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:2','max:50',Rule::unique('taxes','name')->ignore($tax->id)],
            'description' => ['nullable','string','max:150'],
            'rate' => ['nullable','numeric','min:0'],
        ]);

        $tax->update($validated);

        return back();
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();

        return back();
    }
}
