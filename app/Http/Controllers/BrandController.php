<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index()
    {

        $branches = Brand::all();

        return Inertia::render('Setting/Brand/Register',[
            'branches' => $branches,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:2','max:50', 'unique:brands,name'],
            'description' => ['nullable','string','max:150'],
        ]);

        Brand::create($validated);

        return back();
    }


    public function update(Request $request, Brand $branch)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:2','max:50', Rule::unique('brands','name')->ignore($branch)],
            'description' => ['nullable','string','max:150'],
        ]);

        $branch->update($validated);

        return back();
    }


    public function destroy(Brand $branch)
    {
        $branch->delete();

        return back();
    }


}
