@extends('layouts.superadmin')



@section('content')
<div class="w-full mx-auto py-6 sm:px-6 lg:px-8">

    <!-- Date Filter & Export Buttons -->
    <div class="bg-white shadow rounded-lg mb-6 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0 sm:space-x-4">
        <div class="flex items-center space-x-4">
            <label for="min-date" class="text-sm font-medium text-gray-700">From:</label>
            <input type="date" id="min-date" class="border rounded-md p-2 text-sm">

            <label for="max-date" class="text-sm font-medium text-gray-700">To:</label>
            <input type="date" id="max-date" class="border rounded-md p-2 text-sm">
        </div>
        <div id="export-buttons" class="flex flex-wrap gap-2 items-center">
            <!-- Export buttons will be injected here by DataTables -->
        </div>
    </div>

    <!-- Payments -->
    <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Payments</h3>
            <div class="overflow-x-auto">
                <table id="grouped-payments-table" class="w-full divide-y divide-gray-200 display nowrap" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th>Application #</th>
                            <th>Operator</th>
                            <th>Fees</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groupedPayments as $group)
                        <tr>
                            <td>{{ $group['application_number'] }}</td>
                            <td>{{ $group['operator_name'] }}</td>
                            <td>
                                <ul class="list-disc ml-4">
                                    @foreach($group['fees'] as $fee)
                                    <li>{{ $fee['description'] }} – ₱{{ number_format($fee['amount_paid'], 2) }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>₱{{ number_format($group['total_amount'], 2) }}</td>
                            <td>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group['paid_at'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $group['paid_at'] ? 'Paid' : 'Pending' }}
                                </span>
                            </td>
                            <td>{{ $group['paid_at'] ? \Carbon\Carbon::parse($group['paid_at'])->format('M d, Y H:i') : 'Pending' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500">No grouped payments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var table = $('#grouped-payments-table').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                    className: 'bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1.5 px-3 rounded-md shadow-sm text-xs'
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    className: 'export-btn bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm text-xs flex items-center gap-2 transition duration-150',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                var div = document.createElement("div");
                                div.innerHTML = data;

                                // Convert <li> items into newlines
                                var items = div.querySelectorAll("li");
                                if (items.length > 0) {
                                    return Array.from(items).map(li => li.innerText).join("\n");
                                }

                                return div.innerText;
                            }
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                    className: 'bg-red-500 hover:bg-red-600 text-white font-semibold py-1.5 px-3 rounded-md shadow-sm text-xs'
                }
            ],
            order: [],
            initComplete: function() {
                // Move export buttons to custom container
                var btns = $('.dt-buttons').addClass('flex flex-wrap gap-2').children();
                $('#export-buttons').empty().append(btns);
            }
        });

        // Date range filter
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var min = $('#min-date').val();
            var max = $('#max-date').val();
            var date = data[5]; // column index of "Date"

            if (!date) return false;

            var parsedDate = new Date(date);
            if ((min === "" || new Date(min) <= parsedDate) &&
                (max === "" || parsedDate <= new Date(max))) {
                return true;
            }
            return false;
        });

        $('#min-date, #max-date').on('change', function() {
            table.draw();
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Success!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonColor: '#1D2761'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        title: 'Error!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonColor: '#E63946'
    });
</script>
@endif
@endsection