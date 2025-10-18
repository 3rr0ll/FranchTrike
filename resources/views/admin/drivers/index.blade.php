@extends('layouts.admin')

@section('title', 'Drivers')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2" >
Drivers List
</h2>
@endsection

@section('content')
<div class="w-full mt-4">
    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                      </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Drivers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $drivers->count() }}</p>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Valid Licenses</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $drivers->where('license_validity', '>=', now())->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="p-4 bg-white rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>                    
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Expired Licenses</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $drivers->where('license_validity', '<', now())->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 bg-white rounded-lg shadow">
    <div class="overflow-auto">
        <table id="drivers-table" class="table-auto row-border w-full text-left">
            <thead class="bg-gray-50">
                <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                    <th>Name</th>
                    <th>Address</th>
                    <th>License No</th>
                    <th>Validity</th>
                    <th>Nature</th>
                    <th>Contact No</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                <tr>
                    <td>{{ $driver->first_name }} {{ $driver->middle_initial }} {{ $driver->last_name }}</td>
                    <td>{{ $driver->barangay }}, {{ $driver->municipality }}, {{ $driver->province }}</td>
                    <td>{{ $driver->license_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($driver->license_validity)->format('M d, Y') }}</td>
                    <td>{{ $driver->license_nature }}</td>
                    <td>{{ $driver->contact_no }}</td>
                    <td>
                        <a href="{{ route('admin.documents.driver.show', encrypt($driver->driver_id)) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.drivers.edit', encrypt($driver->driver_id)) }}" class="inline-flex items-center text-sm text-yellow-600 hover:text-yellow-900 ml-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#drivers-table').DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            order: [
                [6, 'desc']
            ],
            columnDefs: [{
                targets: 6, 
                orderable: false,
                searchable: false
            }],
            language: {
                search: "Search drivers:",
                lengthMenu: "Show _MENU_ drivers per page",
                info: "Showing _START_ to _END_ of _TOTAL_ drivers",
                infoEmpty: "Showing 0 to 0 of 0 drivers",
                infoFiltered: "(filtered from _MAX_ total drivers)",
                zeroRecords: "No drivers found",
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
                $('.dataTables_filter input').addClass(
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg ml-2'
                );

                var $controls = $('<div class="w-full flex flex-row justify-between items-center mb-4 mr-2"></div>');
                var $length = $('.dataTables_length').css('margin', '0');
                var $search = $('.dataTables_filter').css('margin', '0');
                $controls.append($length).append($search);

                $controls.insertBefore($('#drivers-table').closest('.overflow-auto'));
            }
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6'
        });
    @endif
</script>
@endpush
@endsection