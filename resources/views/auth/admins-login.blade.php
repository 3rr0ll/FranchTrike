<x-guest-layout>
<section class="bg-gray-50 dark:bg-gray-900 min-h-screen">
  <div class="flex min-h-screen">
    <!-- Admin Login Form Side -->
    <div class="flex-1 flex items-center justify-center px-6 py-8 lg:px-8">
      <div class="w-full max-w-md">
        <div class="text-center mb-8">
          <a href="#" class="flex items-center justify-center mb-6 text-2xl font-semibold text-primary-navy dark:text-white">
            <img class="w-8 h-8 mr-2" src="{{ asset('images/logo.png') }}" alt="logo">
            Admin Portal    
          </a>
        </div>
        
        <div class="bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-primary-navy md:text-2xl dark:text-white text-center">
              Admin Login
            </h1>
            
            <x-validation-errors class="mb-4" />

            @session('status')
                <div class="mb-4 font-medium text-sm text-accent-green">
                    {{ $value }}
                </div>
            @endsession

            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-accent-red">
                    {{ session('error') }}
                </div>
            @endif
            
            <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="is_admin_login" value="1">
                
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-primary-navy dark:text-white">Admin Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-navy dark:focus:border-primary-navy" placeholder="admin@example.com" required autofocus autocomplete="username">
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-primary-navy dark:text-white">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-navy focus:border-primary-navy block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-navy dark:focus:border-primary-navy" required autocomplete="current-password">
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                          <input id="remember" name="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-navy dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-navy dark:ring-offset-gray-800">
                        </div>
                        <div class="ml-3 text-sm">
                          <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                        </div>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-navy hover:underline dark:text-white">Forgot password?</a>
                    @endif
                </div>
                <button type="submit" class="w-full text-white bg-primary-navy hover:bg-accent-purple focus:ring-4 focus:outline-none focus:ring-primary-navy font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-navy dark:hover:bg-accent-purple dark:focus:ring-primary-navy">Sign in as Admin</button>
                <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center">
                    <a href="{{ route('login') }}" class="font-medium text-primary-navy hover:underline dark:text-white">← Back to regular login</a>
                </p>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Admin Image Side - Hidden on mobile -->
    <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-center bg-gradient-to-br from-primary-navy to-accent-purple">
      <div class="max-w-md text-center text-white px-8">
        <div class="mb-8">
          <svg class="w-24 h-24 mx-auto mb-6 text-primary-gold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <h2 class="text-3xl font-bold mb-4">Admin Dashboard</h2>
        <p class="text-lg mb-6 text-gray-200">Manage franchise applications, review documents, and oversee system operations.</p>
        <div class="space-y-4">
          <div class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-3 text-primary-gold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span>Review and approve applications</span>
          </div>
          <div class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-3 text-primary-gold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span>Manage operators and drivers</span>
          </div>
          <div class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-3 text-primary-gold" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span>System monitoring and reports</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</x-guest-layout>