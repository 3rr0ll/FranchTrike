@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 sm:px-8 py-8">
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Signatories</h1>
        <button
            class="mt-4 sm:mt-0 px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition"
            onclick="document.getElementById('addSignatoryModal').classList.remove('hidden')"
        >
            Add Signatory
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-100 rounded px-4 py-2">{{ session('success') }}</div>
    @endif

    <div class="p-4 bg-white rounded-lg shadow">
        <div class="overflow-auto">
            <table id="signatories-table" class="w-full text-sm text-left row-border text-black">
                <thead class="bg-gray-50">
                    <tr class="tracking-wider text-gray-500 px-4 py-2 text-left text-md font-medium">
                        <th>#</th>
                        <th>Position Title</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($signatories as $i => $signatory)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $i + 1 }}</td>
                        <td class="px-6 py-3">{{ $signatory->position_title }}</td>
                        <td class="px-6 py-3">{{ $signatory->name }}</td>
                        <td class="px-6 py-3 flex flex-row gap-2">
                            <button
                                class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs"
                                onclick="openEditModal({{ $signatory->id }}, '{{ addslashes($signatory->position_title) }}', '{{ addslashes($signatory->name) }}')"
                            >
                                Edit
                            </button>
                            <form action="{{ route('admin.signatories.destroy', $signatory) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs delete-signatory-btn"
                                    data-name="{{ $signatory->name }}"
                                >
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center px-6 py-4 text-gray-500">No signatories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Signatory Modal -->
    <div id="addSignatoryModal" class="fixed z-30 inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4">Add Signatory</h2>
            <form id="addSignatoryForm" action="{{ route('admin.signatories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="position_title">Position Title</label>
                    <input type="text" id="position_title" name="position_title" class="w-full border rounded px-3 py-2"
                        value="{{ old('position_title') }}" required>
                    @error('position_title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="name">Name</label>
                    <input type="text" id="name" name="name" class="w-full border rounded px-3 py-2"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400"
                        onclick="document.getElementById('addSignatoryModal').classList.add('hidden')"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Signatory Modal -->
    <div id="editSignatoryModal" class="fixed z-30 inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4">Edit Signatory</h2>
            <form id="editSignatoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="edit_position_title">Position Title</label>
                    <input type="text" id="edit_position_title" name="position_title" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-1" for="edit_name">Name</label>
                    <input type="text" id="edit_name" name="name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400"
                        onclick="document.getElementById('editSignatoryModal').classList.add('hidden')"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(id, position_title, name) {
    document.getElementById('editSignatoryModal').classList.remove('hidden');
    var form = document.getElementById('editSignatoryForm');
    form.action = location.pathname.replace(/\/+$/, '') + '/' + id;
    document.getElementById('edit_position_title').value = position_title;
    document.getElementById('edit_name').value = name;
}

// Intercept Add (Create) Signatory submit for confirmation
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable (using similar design as in payments/index.blade.php)
    var table = $('#signatories-table').DataTable({
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],
        order: [
            [1, 'asc']
        ],
        columnDefs: [{
            targets: 3,
            orderable: false,
            searchable: false
        }],
        language: {
            search: "Search signatories:",
            lengthMenu: "Show _MENU_ signatories per page",
            info: "Showing _START_ to _END_ of _TOTAL_ signatories",
            infoEmpty: "Showing 0 to 0 of 0 signatories",
            infoFiltered: "(filtered from _MAX_ total signatories)",
            zeroRecords: "No signatories found",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        // Move controls to the top for flexed styling
        initComplete: function() {
            $('.dataTables_length select').addClass(
                'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg'
            );
            $('.dataTables_filter input').addClass(
                'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg ml-2'
            );
            // Move search+length to top right, like payments table
            var $controls = $('<div class="w-full flex flex-row justify-between items-center mb-4 mr-2"></div>');
            var $length = $('.dataTables_length').css('margin', '0');
            var $search = $('.dataTables_filter').css('margin', '0');
            $controls.append($length).append($search);
            $controls.insertBefore($('#signatories-table').closest('.overflow-auto'));
        }
    });

    // CREATE
    const addForm = document.getElementById('addSignatoryForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Add Signatory?',
                text: "Are you sure you want to add this signatory?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Add'
            }).then((result) => {
                if (result.isConfirmed) {
                    addForm.submit();
                }
            });
        });
    }

    const editForm = document.getElementById('editSignatoryForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Update Signatory?',
                text: "Are you sure you want to update this signatory?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Update'
            }).then((result) => {
                if (result.isConfirmed) {
                    editForm.submit();
                }
            });
        });
    }

    document.querySelectorAll('.delete-signatory-btn').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            let form = btn.closest('form');
            let name = btn.getAttribute('data-name') || 'this signatory';

            Swal.fire({
                title: 'Delete Signatory?',
                text: `Are you sure you want to delete ${name}?`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete'
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
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
});
</script>
@endpush
@endsection

