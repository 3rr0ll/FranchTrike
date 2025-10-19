@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2" >
Super Admin Dashboard
</h2>
@endsection

@section('content')

    <div class="w-full mx-auto py-2 sm:px-6 lg:px-2">
        <!-- Welcome Section -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <h3 class="text-2xl font-bold text-primary-navy mb-2">Welcome, {{ Auth::user()->name }}!</h3>
            <p class="text-primary-navy">Manage users, monitor system activity, and oversee the entire FranchTrike platform.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_users'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Admins -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center text-red-700">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Admins</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['admins'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Operators -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Operators</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['operators'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Applications -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Applications</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_applications'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Approved Franchises -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Approved Franchises</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['approved_franchises'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Expired Franchises -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Expired Franchises</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['expired_franchises'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Users</h3>
                    <div class="flow-root">
                        <ul class="-my-5 divide-y divide-gray-200">
                            @forelse($recent_users as $user)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($user->role->name) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="py-4">
                                <p class="text-sm text-gray-500">No recent users</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('superadmin.users.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View all users
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Applications -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Applications</h3>
                    <div class="flow-root">
                        <ul class="-my-5 divide-y divide-gray-200">
                            @forelse($recent_applications as $application)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">A</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $application->application_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $application->operator->full_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @php
                                        $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'draft' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $color = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="py-4">
                                <p class="text-sm text-gray-500">No recent applications</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Franchise Timeline/Calendar -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-8 mt-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Franchise Timeline</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operator</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($franchise_events as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->application_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @php
                                    $operator = \App\Models\Operator::where('operator_id', $event->operator_id)->first();
                                    @endphp
                                    {{ $operator ? $operator->full_name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->franchise_start_date ? $event->franchise_start_date->format('M d, Y') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->franchise_end_date ? $event->franchise_end_date->format('M d, Y') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No franchise events found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


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

@endsection