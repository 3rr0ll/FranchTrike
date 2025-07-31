@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example cards -->
        <div class="p-4 bg-gray-100 rounded shadow">
            <p class="text-gray-600">Total Drivers</p>
            <p class="text-3xl font-bold">42</p>
        </div>
        <div class="p-4 bg-gray-100 rounded shadow">
            <p class="text-gray-600">Total Operators</p>
            <p class="text-3xl font-bold">12</p>
        </div>
    </div>
</div>
@endsection