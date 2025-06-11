<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;

class OperatorController extends Controller
{
    public function index()
    {
        $operators = Operator::all();
        return view('admin.operators.index', compact('operators'));
    }
}
