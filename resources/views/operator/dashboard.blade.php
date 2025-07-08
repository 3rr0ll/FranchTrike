<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold leading-tight text-gray-800">
                Operator Dashboard
            </h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button type="submit" class="ml-4">
                    Logout
                </x-button>
            </form>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 4v16m8-8H4\' />'"
                title="Apply for Franchise"
                description="Start a new franchise application or check your application status."
                :route="route('operator.create')"
                color="text-blue-500"
                buttonText="Apply Franchise"
            />

            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9\' />'"
                title="View Document Status"
                description="Check the status of your submitted operator and driver documents."
                :route="route('operator.documents.status')"
                color="text-green-500"
                buttonText="View Document Status"
            />

            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z\' />'"
                title="My Drivers"
                description="View and manage your assigned drivers and their details."
                :route="route('operator.driver.index')"
                color="text-yellow-500"
                buttonText="View My Drivers"
            />

            <x-operator-dashboard-card
                :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12h6m2 0a8 8 0 11-16 0 8 8 0 0116 0z\' />'"
                title="Final Franchise Application"
                description="Submit your final franchise application once all documents are approved."
                :route="route('operator.franchise.index')"
                color="text-purple-500"
                buttonText="Franchise Applications"
            />
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
</x-app-layout>
