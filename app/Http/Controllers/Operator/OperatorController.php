<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
    public function index()
    {
        // Raw query or query builder to get operators
        $operators = DB::table('operators')->get();

        // Pass to view
        return view('operator.index', compact('operators'));
    }


    public function create()
    {
        return view('operator.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'barangay' => 'required|string',
            'municipality' => 'required|string',
            'province' => 'required|string',
            'birth_date' => 'required|date',
            'age' => 'required|integer',
            'sex' => 'required|string',
            'civil_status' => 'required|string',
            'contact_no' => 'required|string',
        ]);

        // Add logged in user ID
        $validated['user_id'] = auth()->id();

        Operator::create($validated);

        return redirect()->route('operator.home')->with('success', 'Operator created.');
    }

    public function edit(Operator $operator)
    {
        return view('operator.edit', compact('operator'));
    }

    public function update(Request $request, Operator $operator)
    {
        $validated = $request->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'barangay' => 'required|string',
            'municipality' => 'required|string',
            'province' => 'required|string',
            'birth_date' => 'required|date',
            'age' => 'required|integer',
            'sex' => 'required|string',
            'civil_status' => 'required|string',
            'contact_no' => 'required|string',
        ]);

        $operator->update($validated);

        return redirect()->route('operator.index')->with('success', 'Operator updated.');
    }

    public function destroy(Operator $operator)
    {
        $operator->delete();

        return redirect()->route('operator.index')->with('success', 'Operator deleted.');
    }
}
