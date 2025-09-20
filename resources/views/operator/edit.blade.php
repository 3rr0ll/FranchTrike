
@extends('layouts.operator')

@section('content')
    <div class="w-full mx-auto mt-8 bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-primary-navy mb-6 flex items-center gap-2">
            <svg class="w-7 h-7 text-primary-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19.428 15.341A8 8 0 104.572 15.34"/>
            </svg>
            Edit Profile
        </h2>

        @if(session('status'))
            @if(session('status') !== 'No changes detected.')
                <div class="mb-6 flex items-center gap-2 bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
        @endif

        @if(session('status') === 'No changes detected.')
            <div class="mb-6 flex items-center gap-2 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 px-4 py-3 rounded shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" />
                </svg>
                <span>No changes detected. Nothing was updated.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('operator.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Name Card -->
            <div class="bg-gray-50 rounded-xl shadow p-6 mb-4">
                <h3 class="text-lg font-semibold text-primary-navy mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Change Name
                </h3>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $operator->name) }}"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-gold focus:border-primary-gold px-4 py-2"
                    required
                >
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profile Photo Card -->
            <div class="bg-gray-50 rounded-xl shadow p-6 mb-4">
                <h3 class="text-lg font-semibold text-primary-navy mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Change Profile Photo
                </h3>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="profile_photo">Profile Photo</label>
                <div class="flex items-center gap-4">
                    <div>
                        <input
                            type="file"
                            name="profile_photo"
                            id="profile_photo"
                            accept="image/*"
                            class="block w-full text-sm text-gray-700 border-gray-300 rounded-md shadow-sm focus:ring-primary-gold focus:border-primary-gold file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-gold file:text-white hover:file:bg-primary-navy"
                        >
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG, or GIF. Max 2MB.</p>
                        @error('profile_photo')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @if($operator->profile_photo_path)
                        <img src="{{ $operator->profile_photo_path }}" alt="Profile"
                             class="w-20 h-20 rounded-full object-cover border-2 border-primary-gold shadow">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 border-2 border-gray-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Password Card -->
            <div class="bg-gray-50 rounded-xl shadow p-6 mb-4">
                <h3 class="text-lg font-semibold text-primary-navy mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 11V7a5 5 0 00-10 0v4M5 11h14v10H5z"/>
                    </svg>
                    Change Password
                </h3>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="password">New Password</label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-gold focus:border-primary-gold px-4 py-2 pr-12"
                        autocomplete="new-password"
                        placeholder="Leave blank to keep current password"
                    >
                    <button type="button" data-target="password"
                        class="toggle-password absolute top-1/2 -translate-y-1/2 right-3 px-2 py-1 text-gray-700 hover:bg-gray-100 flex items-center justify-center"
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
                <p class="text-xs text-gray-500 mt-1">Leave blank if you do not want to change your password.</p>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror

                <label class="block text-sm font-medium text-gray-700 mb-1 mt-4" for="password_confirmation">Confirm Password</label>
                <div class="relative">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-gold focus:border-primary-gold px-4 py-2 pr-12"
                        autocomplete="new-password"
                    >
                    <button type="button" data-target="password_confirmation"
                        class="toggle-password absolute top-1/2 -translate-y-1/2 right-3 px-2 py-1 text-gray-700 hover:bg-gray-100 flex items-center justify-center"
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
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-primary-gold hover:bg-primary-navy text-white font-semibold px-6 py-2 rounded shadow transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Profile
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
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
    });
</script>
@endpush
