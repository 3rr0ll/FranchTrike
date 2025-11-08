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
        // Prevent access if the operator already submitted details
        $existing = Operator::where('user_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('operator.dashboard')
                ->with('error', 'You have already submitted your operator details.');
        }

        return view('operator.create');
    }

    public function store(Request $request)
    {
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
                    // If age field is present, ensure it matches the calculated age
                    if ($request->has('age')) {
                        // Calculate age from birth_date at the current date
                        $birthDate = \Carbon\Carbon::parse($value);
                        $ageFromBirthDate = $birthDate->age;
                        $inputAge = (int) $request->input('age');
                        if ($inputAge !== $ageFromBirthDate) {
                            $fail('The entered age does not match the birth date.');
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
                }
            ],
            'sex' => [
                'required',
                'in:Male,Female'
            ],
            'civil_status' => [
                'required',
                'in:Single,Married,Divorced,Widowed,Separated'
            ],
            'contact_no' => [
                'required',
                'string',
                'regex:/^09\d{9}$/',
                'unique:operators,contact_no'
            ],
        ]);
    
        // Automatically assign fixed location values
        $validated['municipality'] = 'Padre Garcia';
        $validated['province'] = 'Batangas';
        $validated['user_id'] = Auth::id();
    
        $userId = Auth::check() ? Auth::id() : null;
    
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
                'created_by' => Auth::user()->name,
                'user_id' => $userId,
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
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->has('age')) {
                        try {
                            $birthDate = \Carbon\Carbon::parse($value);
                            $ageFromBirthDate = $birthDate->age;
                            $inputAge = (int) $request->input('age');
                            if ($inputAge !== $ageFromBirthDate) {
                                $fail('The entered age does not match the birth date.');
                            }
                        } catch (\Exception $e) {
                            // ignore parse error here, caught by earlier validation
                        }
                    }
                }
            ],
            'age' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
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
                }
            ],
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
