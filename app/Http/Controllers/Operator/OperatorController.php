<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


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

        $validated['user_id'] = Auth::user()->id;

        Operator::create($validated);

        \App\Helpers\ActivityLogger::log(
            'operator',
            'created',
            'Operator profile created.',
            [
                'operator_id' => Auth::user()->id,
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_initial' => $validated['middle_initial'] ?? null,
                'barangay' => $validated['barangay'],
                'municipality' => $validated['municipality'],
                'province' => $validated['province'],
                'birth_date' => $validated['birth_date'],
                'age' => $validated['age'],
                'sex' => $validated['sex'],
                'civil_status' => $validated['civil_status'],
                'contact_no' => $validated['contact_no'],
                'created_by' => auth()->user() ? auth()->user()->name : null,
            ]
        );

        return redirect()->route('operator.driver.create')->with('success', 'Operator created.');
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

        return redirect()->route('operator.home')->with('success', 'Operator updated.');
    }

    public function destroy(Operator $operator)
    {
        $operator->delete();

        return redirect()->route('operator.index')->with('success', 'Operator deleted.');
    }
}
