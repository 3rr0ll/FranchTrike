@extends('layouts.superadmin')

@section('content')
    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Fee
            </h2>
            
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('superadmin.payments.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <x-label for="description" value="Fee Description" />
                            <x-input id="description" type="text" name="description" class="mt-1 block w-full" value="{{ old('description') }}" required autofocus />
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <x-label for="amount" value="Amount (₱)" />
                            <x-input id="amount" type="number" name="amount" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('amount') }}" required />
                            @error('amount')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <div class="flex items-center">
                                <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-primary-navy focus:ring-primary-navy border-gray-300 rounded">
                                <x-label for="is_active" value="Active" class="ml-2" />
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Check this if the fee should be available for payment</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('superadmin.payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-navy focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-button class="bg-primary-navy hover:bg-primary-navy/90 focus:bg-primary-navy/90 active:bg-primary-navy/90">
                                Create Fee
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection