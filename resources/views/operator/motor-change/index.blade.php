@extends('layouts.operator')

@section('header')
    <h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
        Motor Change Request History
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto mt-8">
        <div class="bg-white shadow-lg p-8 rounded-2xl">
            <h3 class="text-2xl font-bold mb-6 text-primary-navy">Your Motor Change Requests</h3>
            {{-- Filter by Franchise --}}
            @if($franchiseApplications->count() > 1)
                <form method="GET" action="{{ route('operator.motor-change.index') }}" class="mb-6 flex items-center gap-4">
                    <label for="franchise_application_id" class="font-semibold text-primary-navy">Filter by Franchise:</label>
                    <select name="franchise_application_id" id="franchise_application_id" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-primary-gold focus:border-primary-gold" onchange="this.form.submit()">
                        <option value="">All</option>
                        @foreach($franchiseApplications as $franchise)
                            <option value="{{ $franchise->id }}" {{ $selectedFranchiseId == $franchise->id ? 'selected' : '' }}>
                                {{ $franchise->franchise_no ?? 'Franchise #' . $franchise->id }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif

            @if($requests->isEmpty())
                <div class="text-gray-600 text-lg">
                    You have not submitted any motor change requests yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date Requested</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Franchise No</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Old Motor Details</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($requests as $request)
                                <tr>
                                    <td class="px-4 py-3 text-gray-900">
                                        {{ $request->created_at ? $request->created_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900">
                                        {{ $request->franchiseApplication->franchise_no ?? 'Franchise #' . $request->franchiseApplication->id }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900">
                                        <div>
                                            <div>
                                                <span class="font-semibold">Type:</span>
                                                {{ $request->old_unit_type ? ucfirst($request->old_unit_type) : '-' }}
                                            </div>
                                            <div>
                                                <span class="font-semibold">Make:</span>
                                                {{ $request->oldUnitMake ? $request->oldUnitMake->name : '-' }}
                                            </div>
                                            <div>
                                                <span class="font-semibold">Motor No:</span>
                                                {{ $request->old_motorno ?? '-' }}
                                            </div>
                                            <div>
                                                <span class="font-semibold">Chassis No:</span>
                                                {{ $request->old_chasisno ?? '-' }}
                                            </div>
                                            <div>
                                                <span class="font-semibold">Plate No:</span>
                                                {{ $request->old_platenumber ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 text-base font-semibold rounded-full
                                            @if($request->status == 'approved') bg-green-100 text-green-800
                                            @elseif($request->status == 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection