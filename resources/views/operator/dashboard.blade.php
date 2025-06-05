<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Operators
        </h2>
    </x-slot>

    <div class="py-6 px-4">
        <div class="mb-4">
            <a href="{{ route('operator.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add Operator
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
            <table id="operators-table" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Name</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Municipality</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Contact</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Include jQuery & DataTables CSS/JS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#operators-table').DataTable({
                // Optional: Customize DataTables options here
                pageLength: 10,
                lengthChange: false,
                searching: true,
                ordering: true,
            });
        });
    </script>
</x-app-layout>