<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('admin.drivers.index', compact('drivers'));
    }


    public function edit($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.edit', compact('driver', 'encryptedId'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }

        $validated = $request->validate([
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\-]+$/'],
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\-]+$/'],
            'middle_initial' => ['nullable', 'string', 'max:1', 'regex:/^[A-Za-z]$/'],
            'barangay' => [
                'required',
                'string',
                'max:255'
            ],
            'birth_date' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) > strtotime('-18 years')) {
                        $fail('The driver must be at least 18 years old.');
                    }
                },
            ],
            'age' => [
                'required',
                'integer',
                'min:18',
                'max:80',
            ],
            'sex' => ['required'],
            'civil_status' => ['required'],
            'contact_no' => [
                'required',
                'string',
                'regex:/^09\d{9}$/'
            ],
            'license_no' => [
                'required',
                'string',
                'max:50',
                'unique:drivers,license_no,' . $id . ',driver_id',
                'regex:/^[A-Z]\d{2}-\d{2}-\d{6}$/'
            ],
            'license_validity' => [
                'required',
                'date',
                'after:today',
            ],
            'license_nature' => [
                'required'            ],
        ]);
        // Automatically assign fixed location values
        $validated['municipality'] = 'Padre Garcia';
        $validated['province'] = 'Batangas';
        $driver = Driver::findOrFail($id);

        $originalData = $driver->getAttributes();

        $driver->last_name = $validated['last_name'];
        $driver->first_name = $validated['first_name'];
        $driver->middle_initial = $validated['middle_initial'] ?? null;
        $driver->barangay = $validated['barangay'];
        $driver->municipality = $validated['municipality'];
        $driver->province = $validated['province'];
        $driver->birth_date = $validated['birth_date'];
        $driver->age = $validated['age'];
        $driver->sex = $validated['sex'];
        $driver->civil_status = $validated['civil_status'];
        $driver->contact_no = $validated['contact_no'];
        $driver->license_no = $validated['license_no'];
        $driver->license_validity = $validated['license_validity'];
        $driver->license_nature = $validated['license_nature'];

        // Check for changes before saving
        if (!$driver->isDirty()) {
            return redirect()->route('admin.drivers.index', $encryptedId)->with('info', 'No changes were made to the driver.');
        }

        $driver->save();

        \App\Helpers\ActivityLogger::log(
            'driver',
            'updated',
            'Driver updated successfully.',
            [
                'driver id' => $driver->driver_id,
                'updated_by' => Auth::user() ? Auth::user()->name : null,
                'attributes' => $driver->getAttributes(),
            ]
        );

        return redirect()->route('admin.drivers.index', encrypt($driver->id))->with('success', 'Driver updated successfully.');
    }
}
