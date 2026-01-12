<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BranchController extends Controller
{
    public function index()
    {

        $branches = Branch::all();

        return Inertia::render('Branch/Register',[
            'branches' => $branches,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:2','max:50', 'unique:branches,name'],
            'description' => ['nullable','string','max:150'],
        ]);

        Branch::create($validated);

        return back();
    }


    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => ['required','string','min:2','max:50', Rule::unique('branches','name')->ignore($branch)],
            'description' => ['nullable','string','max:150'],
        ]);

        $branch->update($validated);

        return back();
    }


    public function destroy(Branch $branch)
    {
        $branch->delete();

        return back();
    }


}
