<x-guest-layout>
<section class="min-h-screen flex items-center justify-center bg-gray-100" style="background-image: url('{{ asset('images/login_bg.jpg') }}'); background-size: cover; background-position: center;">
    <div class="w-full min-h-screen flex items-center justify-center px-2 sm:px-6">
      <div class="w-full max-w-5xl mx-auto flex flex-col lg:flex-row items-stretch bg-transparent my-8 sm:my-12">
        <!-- Login Form Side -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center bg-white rounded-l-xl lg:rounded-r-none rounded-xl shadow-2xl border border-gray-200 px-8 py-10">
          <div class="mb-4">
            <a href="{{ url('/') }}" class="inline-flex items-center text-primary-navy hover:text-accent-purple font-medium text-sm transition-colors">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
              </svg>
            </a>
          </div>

          <h2 class="text-2xl font-bold text-primary-navy text-center">Admin Login</h2>

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

          <form class="space-y-5" method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="is_admin_login" value="1">

            <div>
              <label for="email" class="block mb-1 text-sm font-semibold text-primary-navy">Email Address</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary-navy">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                  </svg>
                </span>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                  class="pl-10 pr-3 py-2 w-full border border-gray-300 rounded-lg focus:ring-primary-navy focus:border-primary-navy text-gray-900 bg-white"
                  placeholder="Enter your email" required autofocus autocomplete="username">
              </div>
            </div>
            <div>
              <label for="password" class="block mb-1 text-sm font-semibold text-primary-navy">Password</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary-navy">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                  </svg>
                </span>
                <input type="password" name="password" id="password"
                  class="pl-10 pr-10 py-2 w-full border border-gray-300 rounded-lg focus:ring-primary-navy focus:border-primary-navy text-gray-900 bg-white"
                  placeholder="Enter your password" required autocomplete="current-password">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-primary-navy focus:outline-none" tabindex="-1">
                  <!-- Eye icon (visible by default) -->
                  <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                  <!-- Eye slash icon (hidden by default) -->
                  <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hidden">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                  </svg>
                </button>
              </div>
              <x-validation-errors class="mb-4" />
            </div>
            <div class="flex items-center justify-between">
              <div></div>
              @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-navy hover:underline">Forgot password?</a>
              @endif
            </div>
            <button type="submit"
              class="w-full text-white bg-primary-navy hover:bg-accent-purple transition-colors duration-200 focus:ring-4 focus:outline-none focus:ring-primary-navy font-semibold rounded-lg text-base px-5 py-2.5 shadow">
              Sign in
            </button>
          </form>
        </div>
        <!-- Logo & Welcome Side (collapses on mobile) -->
        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-primary-navy to-accent-purple items-center justify-center rounded-r-xl">
          <div class="w-full flex flex-col items-center justify-center px-8 py-10">
            <img class="w-40 h-40 mb-6 rounded-full border-4 border-primary-white shadow-lg bg-white object-contain"
              src="{{ asset('images/logo.png') }}" alt="Padre Garcia Logo">
            <h2 class="text-xl font-bold mb-2 text-primary-gold">Welcome to Franchise System</h2>
            <h2 class="text-xl font-bold mb-2 text-primary-gold">Admin Portal</h2>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const passwordInput = document.getElementById('password');
      const togglePassword = document.getElementById('togglePassword');
      const eyeIcon = document.getElementById('eyeIcon');
      const eyeSlashIcon = document.getElementById('eyeSlashIcon');

      if (togglePassword) {
        togglePassword.addEventListener('click', function (e) {
          e.preventDefault();
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
          } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
          }
        });
      }
    });
  </script>
</x-guest-layout>