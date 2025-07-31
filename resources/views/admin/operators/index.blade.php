@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Operators List</h1>

    <div class="mb-4 flex gap-2">
        <button id="export-csv" class="px-3 py-1 bg-blue-500 text-white rounded">Export CSV</button>
        <button id="export-json" class="px-3 py-1 bg-green-500 text-white rounded">Export JSON</button>
        <button id="export-txt" class="px-3 py-1 bg-yellow-500 text-white rounded">Export TXT</button>
        <button id="export-sql" class="px-3 py-1 bg-purple-500 text-white rounded">Export SQL</button>
    </div>

    <table id="operators-table" class="table-auto w-full text-left">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Birth Date</th>
                <th>Sex</th>
                <th>Contact No</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($operators as $operator)
            <tr>
                <td>{{ $operator->first_name }} {{ $operator->middle_initial }} {{ $operator->last_name }}</td>
                <td>{{ $operator->barangay }}, {{ $operator->municipality }}, {{ $operator->province }}</td>
                <td>{{ $operator->birth_date->format('M d, Y') }}</td>
                <td>{{ $operator->sex }}</td>
                <td>{{ $operator->contact_no }}</td>
                <td>
                    <a href="{{ route('admin.documents.operator.show', $operator->operator_id) }}" class="text-sm text-blue-600 hover:underline">
                        View Documents
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<!-- Flowbite and simple-datatables scripts -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize DataTable
        const table = document.querySelector("#operators-table");
        const dataTable = new simpleDatatables.DataTable(table);

        // Export handlers
        document.getElementById("export-csv").addEventListener("click", () => {
            simpleDatatables.exportCSV(dataTable, {
                download: true,
                lineDelimiter: "\n",
                columnDelimiter: ";"
            });
        });
        document.getElementById("export-json").addEventListener("click", () => {
            simpleDatatables.exportJSON(dataTable, {
                download: true,
                space: 3
            });
        });
        document.getElementById("export-txt").addEventListener("click", () => {
            simpleDatatables.exportTXT(dataTable, {
                download: true
            });
        });
        document.getElementById("export-sql").addEventListener("click", () => {
            simpleDatatables.exportSQL(dataTable, {
                download: true,
                tableName: "operators"
            });
        });
    });
</script>
@endpush
@endsection