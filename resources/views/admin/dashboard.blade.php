@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Admin Dashboard
</h2>
@endsection

@section('content')
<div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <!-- Total Applications Chart -->
        <div class="bg-white rounded-lg shadow p-4 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900">Applications Over Time</h3>
                <a href="{{ route('admin.franchise.index') }}" class="text-blue-600 hover:text-blue-800" title="View Applications">
                    <x-button>
                        View
                    </x-button>
                </a>
            </div>
            <canvas id="applicationsChart" height="300"></canvas>
        </div>

        <!-- Pending Review Chart -->
        <div class="bg-white rounded-lg shadow p-4 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pending Review Over Time</h3>
                <a href="{{ route('admin.franchise.index', ['status' => 'under_review']) }}" class="ml-4 text-yellow-600 hover:text-yellow-800" title="View Pending Review">
                    <x-button>
                        View
                    </x-button>
                </a>
            </div>
            <canvas id="pendingChart" height="300"></canvas>
        </div>

        <!-- Operators Chart -->
        <div class="bg-white rounded-lg shadow p-4 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Operators Registered Over Time</h3>
                <a href="{{ route('admin.operators.index') }}" class="ml-4 text-green-600 hover:text-green-800" title="View Operators">
                    <x-button>
                        VIew
                    </x-button>
                </a>
            </div>
            <canvas id="operatorsChart" height="300"></canvas>
        </div>

        <!-- Drivers Chart -->
        <div class="bg-white rounded-lg shadow p-4 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Drivers Registered Over Time</h3>
                <a href="{{ route('admin.drivers.index') }}" class="ml-4 text-purple-600 hover:text-purple-800" title="View Drivers">
                    <x-button>
                        View
                    </x-button>
                </a>
            </div>
            <canvas id="driversChart" height="300"></canvas>
        </div>

    </div>


    <!-- Application Status Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Status Distribution</h3>

            <!-- Chart -->
            <div class="w-full h-64">
                <canvas id="statusChart"></canvas>
            </div>

            <div class="mt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Submitted</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $statusCounts['submitted'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Under Review</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $statusCounts['under_review'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Approved</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $statusCounts['approved'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Rejected</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $statusCounts['rejected'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Applications</h3>
            <div class="space-y-3">
                @forelse($recentApplications ?? [] as $application)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>

                        <p class="text-sm font-medium text-gray-900">{{ $application->application_number }}</p>
                        <p class="text-xs text-gray-500">{{$application->operator->full_name }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        @if($application->status == 'approved') bg-green-100 text-green-800
                        @elseif($application->status == 'rejected') bg-red-100 text-red-800
                        @elseif($application->status == 'under_review') bg-yellow-100 text-yellow-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>
                @empty
                <p class="text-sm text-gray-500">No recent applications</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Activity</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">New Applications</span>
                    <span class="text-sm font-medium text-gray-900">{{ $todayStats['new_applications'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Applications Reviewed</span>
                    <span class="text-sm font-medium text-gray-900">{{ $todayStats['applications_reviewed'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Approved Today</span>
                    <span class="text-sm font-medium text-green-600">{{ $todayStats['approved_today'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Rejected Today</span>
                    <span class="text-sm font-medium text-red-600">{{ $todayStats['rejected_today'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Document Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Status</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Pending Review</span>
                    <span class="text-sm font-medium text-yellow-600">{{ $documentStats['pending_review'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Approved Documents</span>
                    <span class="text-sm font-medium text-green-600">{{ $documentStats['approved'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Rejected Documents</span>
                    <span class="text-sm font-medium text-red-600">{{ $documentStats['rejected'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Health</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Active Users</span>
                    <span class="text-sm font-medium text-green-600">{{ $systemStats['active_users'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Routes</span>
                    <span class="text-sm font-medium text-gray-900">{{ $systemStats['total_routes'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">System Status</span>
                    <span class="text-sm font-medium text-green-600">Online</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Total Applications
    new Chart(document.getElementById('applicationsChart'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Applications',
                data: @json($data),
                borderColor: 'rgb(37, 99, 235)',
                backgroundColor: 'rgba(37, 99, 235, 0.2)',
                tension: 0.3,
                fill: true
            }]
        }
    });

    // Pending Review
    new Chart(document.getElementById('pendingChart'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Pending Review',
                data: @json($pendingData),
                borderColor: 'rgb(202, 138, 4)', // yellow-600
                backgroundColor: 'rgba(202, 138, 4, 0.2)',
                tension: 0.3,
                fill: true
            }]
        }
    });

    // Operators
    new Chart(document.getElementById('operatorsChart'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Operators',
                data: @json($operatorsData),
                borderColor: 'rgb(22, 163, 74)', // green-600
                backgroundColor: 'rgba(22, 163, 74, 0.2)',
                tension: 0.3,
                fill: true
            }]
        }
    });

    // Drivers
    new Chart(document.getElementById('driversChart'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Drivers',
                data: @json($driversData),
                borderColor: 'rgb(147, 51, 234)', // purple-600
                backgroundColor: 'rgba(147, 51, 234, 0.2)',
                tension: 0.3,
                fill: true
            }]
        }
    });
</script>

<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Submitted', 'Under Review', 'Approved', 'Rejected'],
            datasets: [{
                label: 'Applications',
                data: [
                    {{ $statusCounts['submitted'] ?? 0 }},
                    {{ $statusCounts['under_review'] ?? 0 }},
                    {{ $statusCounts['approved'] ?? 0 }},
                    {{ $statusCounts['rejected'] ?? 0 }}
                ],
                backgroundColor: [
                    '#3B82F6', // blue
                    '#FACC15', // yellow
                    '#22C55E', // green
                    '#EF4444'  // red
                ],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } // we already show custom legend
            }
        }
    });
</script>
@endpush