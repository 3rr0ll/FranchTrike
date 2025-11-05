@extends('layouts.operator')

@section('title', 'Motor Change Request')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Motor Change Request History
</h2>
@endsection

@section('content')
<div class="w-full mx-auto mt-8 mx-4">
    <div class="bg-white shadow-lg p-4 rounded-lg">

        {{-- Filter by Franchise --}}
        @if($franchiseApplications->count() > 1)
        <form method="GET" action="{{ route('operator.motor-change.index') }}" class="mb-6 flex items-center gap-4">
            <label for="franchise_application_id" class="font-semibold text-primary-navy">Filter by Franchise:</label>
            <select name="franchise_application_id" id="franchise_application_id"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-primary-gold focus:border-primary-gold"
                onchange="this.form.submit()">
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
            <table id="motorChangeTable" class="display w-full rounded-lg">
                <thead class="bg-gray-50">
                    <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                        <th>Date Requested</th>
                        <th>Franchise No</th>
                        <th>Old Motor Details</th>
                        <th>New Motor Details</th>
                        <th>Status</th>
                        <th>Date Approved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>
                            {{ $request->created_at ? $request->created_at->format('M d, Y') : '-' }}
                        </td>
                        <td>
                            {{ $request->franchiseApplication->franchise_no ?? 'Franchise #' . $request->franchiseApplication->id }}
                        </td>
                        <td>
                            <div>
                                <div><span class="font-semibold">Type:</span> {{ $request->old_unit_type ? ucfirst($request->old_unit_type) : '-' }}</div>
                                <div><span class="font-semibold">Make:</span> {{ $request->oldUnitMake ? $request->oldUnitMake->name : '-' }}</div>
                                <div><span class="font-semibold">Motor No:</span> {{ $request->old_motorno ?? '-' }}</div>
                                <div><span class="font-semibold">Chassis No:</span> {{ $request->old_chasisno ?? '-' }}</div>
                                <div><span class="font-semibold">Plate No:</span> {{ $request->old_platenumber ?? '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><span class="font-semibold">Type:</span> {{ $request->new_unit_type ? ucfirst($request->new_unit_type) : '-' }}</div>
                                <div><span class="font-semibold">Make:</span> {{ $request->newUnitMake ? $request->newUnitMake->name : '-' }}</div>
                                <div><span class="font-semibold">Motor No:</span> {{ $request->new_motorno ?? '-' }}</div>
                                <div><span class="font-semibold">Chassis No:</span> {{ $request->new_chasisno ?? '-' }}</div>
                                <div><span class="font-semibold">Plate No:</span> {{ $request->new_platenumber ?? '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                            @if($request->status == 'approved') bg-green-100 text-green-800
                                            @elseif($request->status == 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td> {{ $request->updated_at ? $request->updated_at->format('M d, Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var table = $('#motorChangeTable').DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            order: [
                [0, 'asc']
            ],
            columnDefs: [{
                targets: 5, 
                orderable: false,
                searchable: false
            }],
            language: {
                search: "Search applications:",
                lengthMenu: "Show _MENU_ applications per page",
                info: "Showing _START_ to _END_ of _TOTAL_ applications",
                infoEmpty: "Showing 0 to 0 of 0 applications",
                infoFiltered: "(filtered from _MAX_ total applications)",
                zeroRecords: "No applications found",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            initComplete: function() {
                $('.dataTables_length select').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg'
                );
                // Make the search input smaller (text-xs, px-2, py-1, reduce width)
                $('.dataTables_filter input').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg ml-2 px-2 py-1'
                ).css({
                    'height': '35px',
                    'width': '150px',
                    'max-width': '100%'
                });

                var $controls = $('<div class="w-full flex flex-row justify-between items-center mb-4 mr-2"></div>');
                var $length = $('.dataTables_length').css('margin', '0');
                var $search = $('.dataTables_filter').css('margin', '0');
                $controls.append($length).append($search);

                $controls.insertBefore($('#motorChangeTable').closest('.overflow-x-auto'));
            }
        });
    });
</script>
@endpush