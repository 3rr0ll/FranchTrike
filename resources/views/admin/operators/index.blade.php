@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Operators List</h1>


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
                    <a href="{{ route('admin.documents.operator.show', $operator->operator_id) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Documents
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#operators-table').DataTable({
            columnDefs: [
                { orderable: false, targets: 5 } 
            ]
        });
    });
</script>
@endpush
@endsection