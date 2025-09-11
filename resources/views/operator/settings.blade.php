@extends('layouts.operator')

@section('header')
<h2 class="font-bold text-3xl text-primary-navy mb-8 flex items-center gap-2">
    Account Settings
</h2>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    

    <div class="bg-white shadow rounded-sm p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Change Password</h2>

        {{-- Success Message --}}
        @if (session('status') === 'password-updated')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Password Updated',
                        text: 'Your password has been changed successfully.',
                        icon: 'success',
                        confirmButtonColor: '#0b2545'
                    });
                }
            });
        </script>
        @endif

        {{-- Error Handling Message --}}
        @if (session('status') === 'password-error')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Password Update Failed',
                        text: '{{ session('error_message', 'There was an error updating your password. Please try again.') }}',
                        icon: 'error',
                        confirmButtonColor: '#0b2545'
                    });
                }
            });
        </script>
        @endif

        {{-- Laravel Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input:</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('user-password.update') }}" class="space-y-4" id="change-password-form" autocomplete="off">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <div class="mt-1 relative">
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                        class="block w-full border-gray-300 rounded-sm shadow-sm focus:ring-primary-gold focus:border-primary-gold pr-20" required>
                    <button type="button" data-target="current_password"
                        class="toggle-password absolute top-1/2 -translate-y-1/2 right-2 px-2 py-1 text-gray-700 hover:bg-gray-100 flex items-center justify-center"
                        aria-label="Show/Hide Password">

                        <!-- Eye Icon -->
                        <svg class="w-5 h-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <!-- Eye Off Icon (hidden by default) -->
                        <svg class="w-5 h-5 eye-off-icon hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.11-3.362m3.6-2.586
                      A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.953 9.953 0 01-4.043 5.091M15 12a3 3 0
                      11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                @error('current_password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <div class="mt-1 relative">
                    <input id="password" name="password" type="password" autocomplete="new-password"
                        class="block w-full border-gray-300 rounded-sm shadow-sm focus:ring-primary-gold focus:border-primary-gold pr-20" required minlength="8">
                    <button type="button" data-target="password"
                        class="toggle-password absolute top-1/2 -translate-y-1/2 right-2 px-2 py-1 text-gray-700 hover:bg-gray-100 flex items-center justify-center"
                        aria-label="Show/Hide Password">

                        <!-- Eye Icon -->
                        <svg class="w-5 h-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <!-- Eye Off Icon -->
                        <svg class="w-5 h-5 eye-off-icon hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.11-3.362m3.6-2.586
                      A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.953 9.953 0 01-4.043 5.091M15 12a3 3 0
                      11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1" id="password-requirements">
                    Password must be at least 8 characters
                </p>
                <p class="text-sm text-red-600 mt-1 hidden" id="password-error"></p>
                @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <div class="mt-1 relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                        class="block w-full border-gray-300 rounded-sm shadow-sm focus:ring-primary-gold focus:border-primary-gold pr-20" required>
                    <button type="button" data-target="password_confirmation"
                        class="toggle-password absolute top-1/2 -translate-y-1/2 right-2 px-2 py-1 text-gray-700 hover:bg-gray-100 flex items-center justify-center"
                        aria-label="Show/Hide Password">

                        <!-- Eye Icon -->
                        <svg class="w-5 h-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <!-- Eye Off Icon -->
                        <svg class="w-5 h-5 eye-off-icon hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.11-3.362m3.6-2.586
                      A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.953 9.953 0 01-4.043 5.091M15 12a3 3 0
                      11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18" />
                        </svg>
                    </button>
                    
                </div>
                <p class="text-xs text-gray-500 mt-1" id="password-requirements">
                    Password must be at least 8 characters
                </p>
            </div>

            <div class="pt-2 flex justify-end">
                <x-button type="submit">
                    Update Password
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-password').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const eye = this.querySelector('.eye-icon');
                const eyeOff = this.querySelector('.eye-off-icon');

                if (input.type === 'password') {
                    input.type = 'text';
                    eye.classList.add('hidden');
                    eyeOff.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eye.classList.remove('hidden');
                    eyeOff.classList.add('hidden');
                }
            });
        });

        // Password validation for minimum 8 chars and not same as current password
        const form = document.getElementById('change-password-form');
        const currentPasswordInput = document.getElementById('current_password');
        const newPasswordInput = document.getElementById('password');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', function(e) {
            passwordError.classList.add('hidden');
            passwordError.textContent = '';

            const currentPassword = currentPasswordInput.value;
            const newPassword = newPasswordInput.value;

            if (newPassword.length < 8) {
                e.preventDefault();
                passwordError.textContent = 'New password must be at least 8 characters.';
                passwordError.classList.remove('hidden');
                newPasswordInput.focus();
                return false;
            }

            if (currentPassword && newPassword && currentPassword === newPassword) {
                e.preventDefault();
                passwordError.textContent = 'New password must not be the same as the current password.';
                passwordError.classList.remove('hidden');
                newPasswordInput.focus();
                return false;
            }
        });
    });
</script>