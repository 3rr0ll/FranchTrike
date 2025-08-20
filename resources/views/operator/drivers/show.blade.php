<x-guest-layout>
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-primary-navy">Driver Details</h2>
            <a href="{{ route('operator.driver.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-navy text-white rounded shadow hover:bg-primary-gold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to List
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-8 border border-primary-navy/10">
            <div class="flex items-center gap-4 mb-6">
                <div class="flex-shrink-0 h-14 w-14 rounded-full bg-primary-navy flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">
                        {{ strtoupper(substr($driver->full_name, 0, 1)) }}
                    </span>
                </div>
                <h4 class="text-xl font-semibold text-primary-navy">{{ $driver->full_name }}</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Address</p>
                    <p class="text-base text-gray-800">{{ $driver->full_address }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Birth Date</p>
                    <p class="text-base text-gray-800">{{ $driver->birth_date->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Age</p>
                    <p class="text-base text-gray-800">{{ $driver->age }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Sex</p>
                    <p class="text-base text-gray-800">{{ ucfirst($driver->sex) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Civil Status</p>
                    <p class="text-base text-gray-800">{{ ucfirst($driver->civil_status) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Contact Number</p>
                    <p class="text-base text-gray-800">{{ $driver->contact_no }}</p>
                </div>
            </div>
            <hr class="my-6 border-primary-navy/30">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">License No</p>
                    <p class="text-base text-gray-800">{{ $driver->license_no }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">License Validity</p>
                    <p class="text-base text-gray-800">{{ optional($driver->license_validity)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">License Nature</p>
                    <p class="text-base text-gray-800">{{ $driver->license_nature }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">License Status</p>
                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                        @if($driver->license_status === 'active')
                            bg-green-100 text-green-800
                        @elseif($driver->license_status === 'expired')
                            bg-red-100 text-red-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif
                    ">
                        {{ ucfirst($driver->license_status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
