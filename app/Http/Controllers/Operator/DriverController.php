<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Operator;
use Illuminate\Http\Request;



class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::latest()->paginate(10);
        return view('driver.index', compact('drivers'));
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



        Driver::create($validated);

        return redirect()->route('operator.documents.operator.create')
            ->with('success', 'Driver information submitted successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        return view('driver.show', compact('driver'));
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
            'license_no' => 'required|string|max:50|unique:drivers,license_no,' . $driver->id,
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
