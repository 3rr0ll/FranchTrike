@extends('layouts.operator')

@section('header')  
<h2 class="font-bold text-3xl text-primary-navy flex items-center gap-2">
    Operator Dashboard  
</h2>
@endsection

@section('content')
<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    <!-- Success Message -->
    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: `{{ session('success') }}`,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('operator.home') }}";
                }
            });
        });
    </script>
    @endif
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
            id="alert-{{ $i }}">
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
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('#alerts-container > div[id^="alert-"]');
                alerts.forEach(function(alert) {
                    alert.classList.add('transition', 'opacity-0');
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                });
            }, 4000);
        });
    </script>
    @endif
    {{-- Welcome Note --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Welcome, {{ Auth::user()->name }}!</h3>
        <p class="text-gray-600 mb-2">
            Keep track of your franchise applications, documents, and payments directly from your dashboard.
        </p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        {{-- Active Franchise(s) (latest 2) --}}
        @php
            $activeFranchises = isset($franchiseApplications)
                ? $franchiseApplications->where('status', 'approved')->sortByDesc('submitted_at')->take(2)
                : collect();
        @endphp
        @if($activeFranchises->count())
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Active Franchise(s)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($activeFranchises as $app)
                <div class="border rounded-lg shadow p-5 bg-white flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                    </div>
                    <div class="mt-2">
                        <div class="text-2xl font-bold text-primary-navy mb-1">Franchise #{{ $app->franchise_no ?? '-' }}</div>
                        <div class="text-sm text-gray-600 mb-2">Submitted: {{ $app->submitted_at ? $app->submitted_at->format('M d, Y') : '-' }}</div>
                        @if($app->franchise_end_date)
                        <div class="text-sm text-gray-700">Ends: {{ \Carbon\Carbon::parse($app->franchise_end_date)->format('M d, Y') }}</div>
                        @endif
                    </div>
                    <div class="flex gap-2 mt-2 justify-end">
                        <a href="{{ route('operator.franchise.show', $app) }}" class="inline-block bg-primary-navy text-white px-4 py-2 rounded hover:bg-primary-gold hover:text-primary-navy text-sm">View</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>


    {{-- Quick Stats Section --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 mt-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm text-gray-500">Pending Payments</h4>
            <p class="text-2xl font-bold text-yellow-600">{{ $pendingPaymentsCount }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm text-gray-500">Completed Payments</h4>
            <p class="text-2xl font-bold text-green-600">{{ $completedPaymentsCount }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm text-gray-500">Applications in Progress</h4>
            <p class="text-2xl font-bold text-blue-600">{{ $applicationsInProgressCount }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h4 class="text-sm text-gray-500">Expiring Documents</h4>
            <p class="text-2xl font-bold text-red-600">{{ $expiringDocumentsCount }}</p>
        </div>
    </div>


    {{-- Action Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Add Driver Card with Limit Check --}}
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
            <div class="mb-4">
                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">Add Driver</h3>
            <p class="text-gray-500 text-sm mb-4 text-center">
                @if($driversCount >= 2)
                Driver limit reached ({{ $driversCount }}/2)
                @else
                Register a new driver for your franchise.
                @endif
            </p>
            @if($driversCount >= 2)
            <button onclick="showDriverLimitAlert()" class="inline-flex items-center px-4 py-2 bg-gray-400 text-white text-sm font-bold rounded-lg shadow cursor-not-allowed">
                Add Driver
            </button>
            @else
            <a href="{{ route('operator.driver.create') }}">
                <x-button>
                    Add Driver
                </x-button>
            </a>
            @endif
        </div>

        <x-operator-dashboard-card
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\' />'"
            title="Document Status"
            description="Check the approval and expiration of your documents."
            :route="route('operator.documents.status')"
            color="text-green-500"
            buttonText="View Status" />

        <x-operator-dashboard-card
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z\' />'"
            title="My Drivers"
            description="Manage your assigned drivers."
            :route="route('operator.driver.index')"
            color="text-yellow-500"
            buttonText="Manage Drivers" />

        <x-operator-dashboard-card
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12h6m2 0a8 8 0 11-16 0 8 8 0 0116 0z\' />'"
            title="Franchise Applications"
            description="View all your franchise applications."
            :route="route('operator.franchise.index')"
            color="text-purple-500"
            buttonText="View Applications" />

        <x-operator-dashboard-card
            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1\' />'"
            title="Payment Center"
            description="Pay fees and track payments."
            :route="route('operator.payments.index')"
            color="text-accent-green"
            buttonText="Go to Payments" />

        @php
        // Choose the latest approved application with motor details for change request
        $latestApprovedWithMotor = optional(Auth::user()->operator)
        ->franchiseApplications()
        ->where('status', 'approved')
        ->whereHas('motorDetail')
        ->latest()
        ->first();
        @endphp
    </div>

    {{-- Driver Limit Alert Script --}}
    <script>
        function showDriverLimitAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Driver Limit Reached',
                text: 'You have reached the maximum limit of 2 drivers. Please contact the administrator if you need to add more drivers.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        }
    </script>
    @endsection