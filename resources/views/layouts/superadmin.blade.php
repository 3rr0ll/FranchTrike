<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Admin') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-sans antialiased bg-gray-50">
    {{-- Top Navbar --}}
    <nav class="fixed top-0 z-50 w-full bg-primary-navy border-b border-primary-gold">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <!-- Quick Action Button as Sidebar Button -->
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center justify-center w-10 h-10 text-white bg-primary-gold rounded-full shadow-lg sm:hidden hover:bg-primary-navy hover:text-primary-gold focus:outline-none focus:ring-2 focus:ring-primary-gold transition-all duration-200"
                        title="Open sidebar">
                        <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <span class="sr-only">Open sidebar</span>
                    </button>
                    <a href="{{ route('superadmin.home') }}" class="flex ml-2 md:mr-24">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">Franchtrike Admin</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img src="{{ asset('images/logo.png') }}" alt="User" class="w-10 h-10 rounded-full">
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-sm shadow-sm" id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900" role="none">
                                    {{ Auth::user()->name ?? 'Admin User' }}
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate" role="none">
                                    {{ Auth::user()->email ?? 'admin@example.com' }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{ route('superadmin.home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-navy hover:text-white" role="menuitem">Dashboard</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-navy hover:text-white" role="menuitem">Settings</a>
                                </li>
                                <li>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-accent-red hover:text-white" role="menuitem" onclick="confirmLogout(event)">
                                            sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Sidebar --}}
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-primary-navy border-r border-primary-gold sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full flex flex-col justify-between">
            <nav class="flex-1 px-3 py-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('superadmin.dashboard') }}" class="flex items-center p-2 text-base font-medium text-white rounded-lg hover:bg-primary-gold hover:text-primary-navy transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5 0a2 2 0 002-2V7a2 2 0 00-2-2h-3.5"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.users.index') }}" class="flex items-center p-2 text-base font-medium text-white rounded-lg hover:bg-primary-gold hover:text-primary-navy transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Manage Users
                        </a>
                    </li>
                   
                    <li>
                        <a href="{{ route('superadmin.payments.index') }}" class="flex items-center p-2 text-base font-medium text-white rounded-lg hover:bg-primary-gold hover:text-primary-navy transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Payment Management
                        </a>
                    </li>
                </ul>
            </nav>
           
        </div>
    </aside>


    {{-- Main content --}}
    <div class="p-4 sm:ml-64 mt-16">
        @yield('content')
    </div>

    @livewireScripts
    @stack('scripts')

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
</body>

</html>