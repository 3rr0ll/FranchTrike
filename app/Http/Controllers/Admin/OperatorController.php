<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function index()
    {
        $operators = Operator::all();
        return view('admin.operators.index', compact('operators'));
    }

    public function edit($encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }
        $operator = Operator::findOrFail($id);
        return view('admin.operators.edit', compact('operator', 'encryptedId'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Invalid or tampered link.');
        }

        $validated = $request->validate([
            'last_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'middle_initial' => [
                'nullable',
                'string',
                'max:1',
                'regex:/^[A-Za-z]$/'
            ],
            'barangay' => [
                'required',
                'string',
                'max:255'
            ],
            'birth_date' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) use ($request) {
                    if (strtotime($value) > strtotime('-18 years')) {
                        $fail('The operator must be at least 18 years old.');
                    }
                    // Make sure age matches the birth date if age field is present
                    if ($request->has('age')) {
                        try {
                            $birthDate = \Carbon\Carbon::parse($value);
                            $ageFromBirthDate = $birthDate->age;
                            $inputAge = (int)$request->input('age');
                            if ($inputAge !== $ageFromBirthDate) {
                                $fail('The entered age does not match the birth date.');
                            }
                        } catch (\Exception $e) {
                            // ignore parse error here, caught by earlier validation
                        }
                    }
                },
            ],
            'age' => [
                'required',
                'integer',
                'min:18',
                'max:80',
                function ($attribute, $value, $fail) use ($request) {
                    // If birth_date is present, ensure age matches calculated age
                    if ($request->has('birth_date')) {
                        try {
                            $birthDate = \Carbon\Carbon::parse($request->input('birth_date'));
                            $ageFromBirthDate = $birthDate->age;
                            if ((int)$value !== $ageFromBirthDate) {
                                $fail('The entered age does not match the birth date.');
                            }
                        } catch (\Exception $e) {
                            // ignore parse error here, caught by earlier validation
                        }
                    }
                },
            ],
            'sex' => [
                'required',
                'in:Male,Female'
            ],
            'civil_status' => [
                'required'
            ],
            'contact_no' => [
                'required',
                'string',
                'regex:/^09\d{9}$/'
            ],
        ]);
        $validated['municipality'] = 'Padre Garcia';
        $validated['province'] = 'Batangas';

        $operator = Operator::findOrFail($id);

        $operator->last_name = $validated['last_name'];
        $operator->first_name = $validated['first_name'];
        $operator->middle_initial = $validated['middle_initial'] ?? null;
        $operator->barangay = $validated['barangay'];
        $operator->municipality = $validated['municipality'];
        $operator->province = $validated['province'];
        $operator->birth_date = $validated['birth_date'];
        $operator->age = $validated['age'];
        $operator->sex = $validated['sex'];
        $operator->civil_status = $validated['civil_status'];
        $operator->contact_no = $validated['contact_no'];

        // Use isDirty to check if any attributes have changed
        if (!$operator->isDirty()) {
            return redirect()
                ->route('admin.operators.index', encrypt($operator->id))
                ->with('info', 'No changes detected. Nothing was updated.');
        }

        $operator->save();

        \App\Helpers\ActivityLogger::log(
            'operator',
            'updated',
            'Operator updated successfully.',
            [
                'operator id' => $operator->operator_id,
                'updated_by' => Auth::user() ? Auth::user()->name : null,
                'attributes' => $operator->getAttributes(),
            ]
        );

        return redirect()
            ->route('admin.operators.index', encrypt($operator->id))
            ->with('success', 'Operator updated successfully.');
    }
}
