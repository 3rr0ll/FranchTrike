@extends('layouts.superadmin')

@section('title', 'Database Tables')

@section('header')
    <h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
        Database Tables
    </h2>
@endsection

@section('content')
<div class="p-6 bg-white shadow rounded-lg">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-4">
        <div>
            <label for="tableFilter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Table Name</label>
            <select id="tableFilter" class="border rounded-lg p-2 focus:ring focus:ring-primary-navy focus:border-primary-navy">
                <option value="">All Tables</option>
                @php
                    $tableGroups = collect($tableNames ?? [])->groupBy(function($name) {
                        return explode('_', $name, 2)[0];
                    });
                @endphp
                @foreach($tableGroups as $prefix => $tables)
                    <optgroup label="{{ strtoupper($prefix) }}">
                    @foreach($tables as $table)
                        <option value="{{ $table }}">{{ $table }}</option>
                    @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table id="databaseTable" class="min-w-full row-border">
            <thead class="bg-gray-50">
                <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Table Name</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tableNames ?? [] as $i => $name)
                @php
                    $category = explode('_', $name, 2)[0];
                @endphp
                <tr>
                    <td class="px-4 py-2">{{ $i + 1 }}</td>
                    <td class="px-4 py-2 table-name">{{ $name }}</td>
                    <td class="px-4 py-2 table-category">{{ strtoupper($category) }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('superadmin.database.show', ['table' => $name]) }}"
                            class="text-blue-600 hover:underline">View Data</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        var table = $('#databaseTable').DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            order: [[1, 'asc']],
            columnDefs: [
                { targets: 0, width: "40px", orderable: false }
            ],
            language: {
                search: "Search table:",
                lengthMenu: "Show _MENU_ tables per page",
                info: "Showing _START_ to _END_ of _TOTAL_ tables",
                zeroRecords: "No tables found",
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
                var $controls = $('<div class="w-full flex flex-row justify-between items-center mb-4 mr-1"></div>');
                var $length = $('.dataTables_length').css('margin', '0');
                var $search = $('.dataTables_filter').css('margin', '0');
                $controls.append($length).append($search);
                $controls.insertBefore($('#databaseTable').closest('.overflow-x-auto'));
            }
        });

        // Table name filter
        $('#tableFilter').on('change', function() {
            var selected = $(this).val();
            if(selected) {
                table
                    .column(1) // Table name column
                    .search('^' + selected + '$', true, false)
                    .draw();
            } else {
                table
                    .column(1)
                    .search('')
                    .draw();
            }
        });
    });
</script>
@endpush
