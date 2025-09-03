@extends('layouts.admin')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2" >
Admin Dashboard
</h2>
@endsection

@section('content')
<div class="p-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Applications -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Applications</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalApplications ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Review</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingReview ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Operators -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Operators</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalOperators ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Drivers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Drivers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalDrivers ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Status Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Status Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Status Distribution</h3>
            <div class="space-y-4">
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

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">


        <a href="{{ route('admin.franchise.export') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-600 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Export Data</h3>
                    <p class="text-sm text-gray-600">Export applications and reports</p>
                </div>
            </div>
        </a>
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