<x-guest-layout title="Reset Password">
    <div class="flex justify-center items-center min-h-screen bg-[#e5e5e4]">
        <div class="w-full max-w-md mx-auto mt-12 bg-white rounded-2xl shadow-2xl border border-gray-100 p-8 relative">
            <a href="{{ route('login') }}" class="absolute top-6 left-6 flex items-center text-primary-navy hover:text-primary-gold transition-colors">
                <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="FranchTrike Logo" class="h-12">
            </div>

            <h2 class="text-2xl font-bold text-primary-navy mb-2 text-center">Forgot Password</h2>
            <p class="mb-6 text-sm text-gray-600 text-center">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </p>

            @if (session('status'))
            <div class="mb-4 flex items-center gap-2 bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded shadow">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary-navy pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-navy focus:border-primary-navy px-4 py-2"
                        >
                    </div>
                </div>

                <x-button type="submit" class="w-full justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Email Password Reset Link
                </x-button>
            </form>
        </div>

    </div>
</x-guest-layout>