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
        return view('admin.drivers.edit', compact('driver','encryptedId'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:2',
            'barangay' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'age' => 'required|integer|min:0',
            'sex' => 'required|string|max:10',
            'civil_status' => 'required|string|max:50',
            'contact_no' => 'required|string|max:20',
            'license_no' => 'required|string|max:50',
            'license_validity' => 'required|date',
            'license_nature' => 'required|string|max:50',
        ]);

        $driver = Driver::findOrFail($id);

        $originalData = $driver->getAttributes();

        $driver->last_name = $request->input('last_name');
        $driver->first_name = $request->input('first_name');
        $driver->middle_initial = $request->input('middle_initial');
        $driver->barangay = $request->input('barangay');
        $driver->municipality = $request->input('municipality');
        $driver->province = $request->input('province');
        $driver->birth_date = $request->input('birth_date');
        $driver->age = $request->input('age');
        $driver->sex = $request->input('sex');
        $driver->civil_status = $request->input('civil_status');
        $driver->contact_no = $request->input('contact_no');
        $driver->license_no = $request->input('license_no');
        $driver->license_validity = $request->input('license_validity');
        $driver->license_nature = $request->input('license_nature');

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
