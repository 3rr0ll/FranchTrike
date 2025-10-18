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
        ]);

        $operator = Operator::findOrFail($id);

        $operator->last_name = $request->input('last_name');
        $operator->first_name = $request->input('first_name');
        $operator->middle_initial = $request->input('middle_initial');
        $operator->barangay = $request->input('barangay');
        $operator->municipality = $request->input('municipality');
        $operator->province = $request->input('province');
        $operator->birth_date = $request->input('birth_date');
        $operator->age = $request->input('age');
        $operator->sex = $request->input('sex');
        $operator->civil_status = $request->input('civil_status');
        $operator->contact_no = $request->input('contact_no');

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
