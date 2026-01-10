<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UnitController extends Controller
{
    public function index()
    {

        $units = Unit::all();

        return Inertia::render("Unit/Register",[
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:2','max:50','unique:units,name'],
            'description' => ['nullable','string','max:255'],
        ]);

        $unit = Unit::create($validated);

        return back();
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:2','max:50',Rule::unique('units','name')->ignore($unit)],
            'description' => ['nullable','string','max:255'],
        ]);

        $unit->update($validated);

        return back();
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return back();
    }
}
