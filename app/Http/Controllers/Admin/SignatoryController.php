<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signatory;
use Illuminate\Http\Request;

class SignatoryController extends Controller
{
    public function index()
    {
        $signatories = Signatory::orderBy('position_title')->get();
        return view('admin.signatories.index', compact('signatories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        Signatory::create($validated);
        return back()->with('success', 'Signatory added successfully.');
    }

    public function update(Request $request, Signatory $signatory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $signatory->update($validated);
        return back()->with('success', 'Signatory updated successfully.');
    }

    public function destroy(Signatory $signatory)
    {
        $signatory->delete();
        return back()->with('success', 'Signatory deleted.');
    }
}
