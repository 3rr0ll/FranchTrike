<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">Operators List</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow p-6 rounded-lg">
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
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#operators-table').DataTable();
        });
    </script>
    @endpush

</x-app-layout>