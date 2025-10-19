@extends('layouts.admin')

@section('title', 'Monthly Report')

@section('header')
<div class="flex justify-between items-center mb-6">
    <h2 class="font-bold text-3xl text-primary-navy">MONTHLY REPORT ON MTFRB</h2>

    <!-- Print Button -->
    <button onclick="printReport()"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow print:hidden">
        Print Report
    </button>
</div>
@endsection

@section('content')
<div id="report-content" class="bg-white shadow rounded-lg p-6 overflow-auto">

    <form method="GET" class="mb-4 flex items-center gap-2 print:hidden">
        <label for="year" class="text-gray-700 font-medium">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()" 
            class="border border-gray-300 rounded p-2">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>

    <table class="min-w-full border text-sm text-left print:!text-xs">
        <thead class="bg-gray-100 print:bg-gray-200">
            <tr>
                <th class="border px-2 py-1">Route</th>
                @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                    <th class="border px-2 py-1 text-right">{{ $m }}</th>
                @endforeach
                <th class="border px-2 py-1 text-right">Total Amount</th>
            </tr>
        </thead>

        <tbody>
            @foreach($monthlyData as $routeName => $months)
                <tr class="border-b">
                    <td class="font-semibold border px-2 py-1">{{ $routeName }}</td>
                    @foreach($months as $amount)
                        <td class="border px-2 py-1 text-right">₱{{ number_format($amount, 2) }}</td>
                    @endforeach
                    <td class="font-semibold text-right border px-2 py-1">
                        ₱{{ number_format(array_sum($months), 2) }}
                    </td>
                </tr>
            @endforeach
            
            <tr class="bg-gray-100 font-bold border-b">
                <td class="border px-2 py-1">Overall Total</td>
                @foreach($overall as $amount)
                    <td class="border px-2 py-1 text-right">₱{{ number_format($amount, 2) }}</td>
                @endforeach
                <td class="border px-2 py-1 text-right">₱{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tbody>

        <tfoot class="bg-gray-100 font-semibold print:bg-gray-200">
            <tr>
                <td class="border px-2 py-1 text-right">Total:</td>
                @foreach($overall as $val)
                    <td class="border px-2 py-1 text-right">
                        {{ $val > 0 ? '₱' . number_format($val, 2) : '' }}
                    </td>
                @endforeach
                <td class="border px-2 py-1 text-right">
                    ₱{{ number_format($grandTotal, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>

@push('styles')
<style>
    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
            font-size: 12px !important;
        }
        th, td {
            border: 1px solid #000 !important;
            padding: 4px !important;
        }
        .print\:hidden {
            display: none !important;
        }
        @page {
            size: landscape;
            margin: 1cm;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function printReport() {
    var printContents = document.getElementById('report-content').innerHTML;
    var originalContents = document.body.innerHTML;

    // Create a wrapper for print styling as in provided receipt example, with left/right margin
    var wrapper = document.createElement('div');

    wrapper.style.padding = '20px';
    wrapper.innerHTML = printContents;

    document.body.innerHTML = wrapper.outerHTML;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>
@endpush

@endsection
