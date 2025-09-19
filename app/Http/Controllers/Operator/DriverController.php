<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $operator = $user ? $user->operator : null;
        $drivers = $operator ? $operator->drivers()->latest()->get() : collect();
        return view('operator.drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('driver.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'barangay' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'age' => 'required|integer|min:18|max:100',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'contact_no' => 'required|string|max:20',
            'license_no' => 'required|string|max:50|unique:drivers,license_no',
            'license_validity' => 'required|date|after:today',
            'license_nature' => 'required|in:Professional,Non-Professional,Student,Restriction 1,Restriction 2',
        ]);

        $operator = Auth::user()->operator;
        $validated['operator_id'] = $operator->operator_id;

        Driver::create($validated);

        \App\Helpers\ActivityLogger::log(
            'driver',
            'created',
            'Operator created a driver profile.',
            [
                'operator_id' => $operator->operator_id,
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
                'license_no' => $validated['license_no'],
                'license_validity' => $validated['license_validity'],
                'license_nature' => $validated['license_nature'],
                'created_by' => auth()->user() ? auth()->user()->name : null,
            ]
        );

        return redirect()->route('operator.documents.operator.create')
            ->with('success', 'Driver information submitted successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        return view('operator.drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        return view('driver.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'barangay' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'age' => 'required|integer|min:18|max:100',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'contact_no' => 'required|string|max:20',
            'license_no' => 'required|string|max:50|unique:drivers,license_no,' . $driver->driver_id,
            'license_validity' => 'required|date|after:today',
            'license_nature' => 'required|in:Professional,Non-Professional,Student,Restriction 1,Restriction 2',
        ]);

        $driver->update($validated);

        return redirect()->route('operator.home')
            ->with('success', 'Driver information updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('operator.home')
            ->with('success', 'Driver deleted successfully!');
    }
}
