@extends('layouts.operator')

@section('header')
    <h2 class=" mt-5 text-2xl font-bold leading-tight text-gray-800">
        Operator Dashboard
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Alerts Section --}}
        @if(isset($alerts) && count($alerts))
        <div class="mb-6 space-y-3" id="alerts-container">
            @foreach($alerts as $i => $alert)
            <div 
                class="flex items-center p-4 rounded shadow text-sm
                        @if($alert['type'] === 'success') bg-green-100 text-green-800 border-l-4 border-green-500
                        @elseif($alert['type'] === 'danger') bg-red-100 text-red-800 border-l-4 border-red-500
                        @elseif($alert['type'] === 'warning') bg-yellow-100 text-yellow-800 border-l-4 border-yellow-500
                        @else bg-blue-100 text-blue-800 border-l-4 border-blue-500 @endif"
                id="alert-{{ $i }}"
            >
                @if($alert['type'] === 'success')
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                @elseif($alert['type'] === 'danger')
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                @elseif($alert['type'] === 'warning')
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" />
                </svg>
                @else
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" />
                </svg>
                @endif
                <span>{!! $alert['message'] !!}</span>
            </div>
            @endforeach
        </div>
        <script>
            // Make alerts disappear after 4 seconds
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const alerts = document.querySelectorAll('#alerts-container > div[id^="alert-"]');
                    alerts.forEach(function(alert) {
                        alert.classList.add('transition', 'opacity-0');
                        setTimeout(function() {
                            alert.style.display = 'none';
                        }, 500); // fade out duration
                    });
                }, 4000);
            });
        </script>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 4v16m8-8H4\' />'"
                title="Apply for Franchise"
                description="Start a new franchise application or check your application status."
                :route="route('operator.create')"
                color="text-blue-500"
                buttonText="Apply Franchise" />

            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\' />'"
                title="View Document Status"
                description="Check the status of your submitted operator and driver documents."
                :route="route('operator.documents.status')"
                color="text-green-500"
                buttonText="View Document Status" />

            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z\' />'"
                title="My Drivers"
                description="View and manage your assigned drivers and their details."
                :route="route('operator.driver.index')"
                color="text-yellow-500"
                buttonText="View My Drivers" />

            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12h6m2 0a8 8 0 11-16 0 8 8 0 0116 0z\' />'"
                title="Final Franchise Application"
                description="Submit your final franchise application once all documents are approved."
                :route="route('operator.franchise.index')"
                color="text-purple-500"
                buttonText="Franchise Applications" />

            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1\' />'"
                title="Payment Center"
                description="Pay fees and view your payment history for franchise applications."
                :route="route('operator.payments.index')"
                color="text-accent-green"
                buttonText="Payment Center" />
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Welcome, {{ Auth::user()->name }}!</h3>
            <p class="text-gray-600 mb-2">
                Use the dashboard to manage your franchise applications, monitor document statuses, and oversee your drivers.
            </p>
            <ul class="list-disc list-inside text-gray-500">
                <li>Start a new franchise application or continue an existing one.</li>
                <li>Track the approval status of your submitted documents.</li>
                <li>View and manage your drivers' information.</li>
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmLogout(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endpush