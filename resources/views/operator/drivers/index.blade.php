@extends('layouts.operator')

@section('title', 'Drivers')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy flex items-center gap-2">
    Drivers
</h2>
@endsection

@section('content')
<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    @if ($drivers->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($drivers as $driver)
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between h-full">
            <div>
                <h2 class="text-xl font-semibold text-primary-navy mb-2">
                    {{ $driver->first_name }} {{ $driver->middle_initial }} {{ $driver->last_name }}
                </h2>
                <div class="mb-2">
                    <span class="text-gray-500 text-sm">Address:</span>
                    <span class="text-gray-800">{{ $driver->barangay }}, {{ $driver->municipality }}, {{ $driver->province }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-gray-500 text-sm">License No:</span>
                    <span class="text-gray-800">{{ $driver->license_no }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-gray-500 text-sm">Validity:</span>
                    <span class="text-gray-800">{{ \Carbon\Carbon::parse($driver->license_validity)->format('M d, Y') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-gray-500 text-sm">Nature:</span>
                    <span class="text-gray-800">{{ $driver->license_nature }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-gray-500 text-sm">Contact No:</span>
                    <span class="text-gray-800">{{ $driver->contact_no }}</span>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <x-button
                    size="sm"
                    type="button"
                    class="open-details-modal"
                    data-full-name="{{ $driver->first_name }} {{ $driver->middle_initial }} {{ $driver->last_name }}"
                    data-full-address="{{ $driver->barangay }}, {{ $driver->municipality }}, {{ $driver->province }}"
                    data-birth-date="{{ $driver->birth_date ? \Carbon\Carbon::parse($driver->birth_date)->format('F d, Y') : '' }}"
                    data-age="{{ $driver->age ?? '' }}"
                    data-sex="{{ ucfirst($driver->sex ?? '') }}"
                    data-civil-status="{{ ucfirst($driver->civil_status ?? '') }}"
                    data-contact-no="{{ $driver->contact_no ?? '' }}"
                    data-license-no="{{ $driver->license_no ?? '' }}"
                    data-license-validity="{{ $driver->license_validity ? \Carbon\Carbon::parse($driver->license_validity)->format('F d, Y') : '' }}"
                    data-license-nature="{{ $driver->license_nature ?? '' }}"
                    data-license-status="{{ $driver->license_status ?? '' }}">
                    View Details
                </x-button>
            </div>


        </div>
        @endforeach
    </div>
    @else
    <p class="text-gray-500">You currently have no assigned drivers.</p>
    @endif

    {{-- Details Modal --}}
    <div id="details-modal" class="fixed inset-0 z-[60] hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/50" id="details-modal-backdrop"></div>
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 p-6 flex flex-col">
            <button id="close-details-modal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold focus:outline-none" aria-label="Close">&times;</button>
            <div class="flex items-center gap-4 mb-6">
                <div class="flex-shrink-0 h-16 w-16 rounded-full bg-primary-navy flex items-center justify-center">
                    <span class="text-3xl font-bold text-white" id="modal-initial">
                    </span>
                </div>
                <h4 class="text-2xl font-semibold text-primary-navy" id="modal-full-name"></h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Address</p>
                    <p class="text-base text-gray-800" id="modal-full-address"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Birth Date</p>
                    <p class="text-base text-gray-800" id="modal-birth-date"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Age</p>
                    <p class="text-base text-gray-800" id="modal-age"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Sex</p>
                    <p class="text-base text-gray-800" id="modal-sex"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Civil Status</p>
                    <p class="text-base text-gray-800" id="modal-civil-status"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Contact Number</p>
                    <p class="text-base text-gray-800" id="modal-contact-no"></p>
                </div>
            </div>
            <hr class="my-4 border-primary-navy/30">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">License No</p>
                    <p class="text-base text-gray-800" id="modal-license-no"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">License Validity</p>
                    <p class="text-base text-gray-800" id="modal-license-validity"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">License Nature</p>
                    <p class="text-base text-gray-800" id="modal-license-nature"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">License Status</p>
                    <span id="modal-license-status" class="inline-block px-2 py-1 rounded-full text-xs font-semibold"></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@livewireScripts
@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openButtons = document.querySelectorAll('.open-details-modal');
        const modal = document.getElementById('details-modal');
        const closeBtn = document.getElementById('close-details-modal');
        const backdrop = document.getElementById('details-modal-backdrop');

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        openButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Fill modal fields
                document.getElementById('modal-full-name').textContent = this.dataset.fullName;
                document.getElementById('modal-initial').textContent = this.dataset.fullName.charAt(0);
                document.getElementById('modal-full-address').textContent = this.dataset.fullAddress;
                document.getElementById('modal-birth-date').textContent = this.dataset.birthDate;
                document.getElementById('modal-age').textContent = this.dataset.age;
                document.getElementById('modal-sex').textContent = this.dataset.sex;
                document.getElementById('modal-civil-status').textContent = this.dataset.civilStatus;
                document.getElementById('modal-contact-no').textContent = this.dataset.contactNo;
                document.getElementById('modal-license-no').textContent = this.dataset.licenseNo;
                document.getElementById('modal-license-validity').textContent = this.dataset.licenseValidity;
                document.getElementById('modal-license-nature').textContent = this.dataset.licenseNature;

                // License status badge
                const statusEl = document.getElementById('modal-license-status');
                statusEl.textContent = this.dataset.licenseStatus;
                statusEl.className = 'inline-block px-2 py-1 rounded-full text-xs font-semibold';
                if (this.dataset.licenseStatus === 'active') {
                    statusEl.classList.add('bg-green-100', 'text-green-800');
                } else if (this.dataset.licenseStatus === 'expired') {
                    statusEl.classList.add('bg-red-100', 'text-red-800');
                } else {
                    statusEl.classList.add('bg-gray-100', 'text-gray-800');
                }

                openModal();
            });
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);
    });
</script>