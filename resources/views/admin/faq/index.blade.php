@extends('layouts.admin')

@section('title', 'FAQ Management')

@section('header')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">FAQ Management</h1>
</div>
@endsection

@section('content')
<div class="p-6 bg-white rounded-xl shadow ">

    <!-- Add FAQ Button -->
    <div class="flex justify-end mb-4">
        <button
            class="bg-blue-600 text-white px-4 py-2 rounded"
            onclick="openFaqModal('create')"
            id="add-faq-btn">Add FAQ
        </button>
    </div>
    <div class="overflow-x-auto">

        <table id="faqTable" class="w-full mt-4 row-border">
            <thead>
                <tr">
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Question</th>
                    <th class="px-4 py-2">Answer</th>
                    <th class="px-4 py-2">Actions</th>
                    </tr>
            </thead>
            <tbody>
                @foreach($faqs as $faq)
                <tr>
                    <td>{{ $faq->category ?? 'General' }}</td>
                    <td>{{ $faq->question }}</td>
                    <td>{{ $faq->answer }}</td>
                    <td>
                        <div class="flex gap-2">
                            <button
                                type="button"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded edit-faq-btn"
                                data-id="{{ $faq->id }}"
                                data-question="{{ $faq->question }}"
                                data-answer="{{ $faq->answer }}"
                                data-category="{{ $faq->category }}"
                                onclick="openFaqModal('edit', this)">
                                Edit
                            </button>
                            <form action="{{ route('admin.faq.destroy', $faq) }}" method="POST" class="inline delete-faq-form">
                                @csrf @method('DELETE')
                                <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded delete-faq-btn">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- FAQ Modal -->
<div
    id="faq-modal"
    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <button
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl"
            onclick="closeFaqModal()"
            aria-label="Close">&times;</button>
        <h3 id="faq-modal-title" class="text-lg font-bold mb-4">Add FAQ</h3>
        <form id="faq-modal-form" method="POST">
            @csrf
            <input type="hidden" name="_method" id="faq-modal-method" value="POST">
            <div class="mb-4">
                <label class="block font-semibold mb-1">Question</label>
                <input type="text" name="question" id="faq-question" class="w-full border rounded px-3 py-2" required maxlength="255">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Answer</label>
                <textarea name="answer" id="faq-answer" class="w-full border rounded px-3 py-2" required rows="4"></textarea>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Category</label>
                <input type="text" name="category" id="faq-category" class="w-full border rounded px-3 py-2" maxlength="100" placeholder="General">
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeFaqModal()" class="px-4 py-2 mr-2 rounded border">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded" id="faq-modal-submit-btn">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#faqTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [
                [0, 'asc']
            ], // sort by category
        });

        // Style search input
        $('#faqTable_filter input').addClass(
            'border rounded-lg p-2 focus:ring focus:ring-blue-500 focus:border-blue-500'
        );

        // Add margin below the search bar
        $('#faqTable_filter').addClass('mb-3');
    });

    function openFaqModal(mode, btn = null) {
        const modal = document.getElementById('faq-modal');
        const form = document.getElementById('faq-modal-form');
        const title = document.getElementById('faq-modal-title');
        const methodInput = document.getElementById('faq-modal-method');
        const submitBtn = document.getElementById('faq-modal-submit-btn');

        // Reset form
        form.reset();
        document.getElementById('faq-question').value = '';
        document.getElementById('faq-answer').value = '';
        document.getElementById('faq-category').value = '';

        if (mode === 'create') {
            title.textContent = 'Add FAQ';
            form.action = "{{ route('admin.faq.store') }}";
            methodInput.value = 'POST';
            submitBtn.textContent = 'Add';
        } else if (mode === 'edit' && btn) {
            title.textContent = 'Edit FAQ';
            const id = btn.getAttribute('data-id');
            const question = btn.getAttribute('data-question');
            const answer = btn.getAttribute('data-answer');
            const category = btn.getAttribute('data-category');

            document.getElementById('faq-question').value = question;
            document.getElementById('faq-answer').value = answer;
            document.getElementById('faq-category').value = category ?? '';

            // Set form action to update route
            form.action = "{{ url('admin/faq') }}/" + id;
            methodInput.value = 'PUT';
            submitBtn.textContent = 'Update';
        }
        modal.classList.remove('hidden');
    }

    function closeFaqModal() {
        document.getElementById('faq-modal').classList.add('hidden');
    }

    // Optional: Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closeFaqModal();
    });

    // SweetAlert2 for Delete Confirmation
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-faq-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = btn.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete this FAQ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // SweetAlert2 for success/error messages from session
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
        @endif
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            timer: 2000,
            showConfirmButton: false
        });
        @endif
    });
</script>
@endsection