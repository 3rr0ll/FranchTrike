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
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-white rounded-lg sm:hidden hover:bg-primary-gold hover:text-primary-navy focus:outline-none focus:ring-2 focus:ring-primary-gold">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="{{ route('admin.home') }}" class="flex ml-2 md:mr-24">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">Franchtrike Admin</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm aria-expanded=" false" data-dropdown-toggle="dropdown-user">
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
                                    <a href="{{ route('admin.home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-navy hover:text-white" role="menuitem">Dashboard</a>
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
        <div class="h-full px-3 pb-4 overflow-y-auto bg-primary-navy">
            <ul class="space-y-2 font-medium">
                <li>
                    @php $isActive = request()->routeIs('admin.home'); @endphp
                    <a href="{{ route('admin.home') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('admin.operators.*'); @endphp
                    <a href="{{ route('admin.operators.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.009 10.009 0 0 0 10 0Zm6.613 4.614a8.523 8.523 0 0 1 1.93 2.302 8.243 8.243 0 0 1-.778 2.104l-.004-.007a4.786 4.786 0 0 0-1.01 2.805 4.49 4.49 0 0 0-1.858 2.024 4.504 4.504 0 0 0-1.858-2.024 4.49 4.49 0 0 0-1.01-2.805l-.004.007a8.243 8.243 0 0 1-.778-2.104 8.523 8.523 0 0 1 1.93-2.302A8.523 8.523 0 0 1 10 4.614a8.523 8.523 0 0 1 6.613 0Z" />
                        </svg>
                        <span class="ms-3">Operators</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('admin.drivers.*'); @endphp
                    <a href="{{ route('admin.drivers.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                            <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        <span class="ms-3">Drivers</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('admin.franchise.*'); @endphp
                    <a href="{{ route('admin.franchise.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        <span class="ms-3">Franchise Applications</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('admin.motor-details.*'); @endphp
                    <a href="{{ route('admin.motor-details.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z" />
                            <path d="M12.5 8a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                        </svg>
                        <span class="ms-3">Motor Details</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('admin.motor-change.*'); @endphp
                    <a href="{{ route('admin.motor-change.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v7a2 2 0 002 2h6l4 3v-3h2a2 2 0 002-2V5a2 2 0 00-2-2H4z"/>
                        </svg>
                        <span class="ms-3">Motor Change Requests</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>


    {{-- Main content --}}
    <div class="p-4 sm:ml-64 mt-16 bg-[#e5e5e4]" style="min-height: 100vh;">
        @hasSection('header')
            <div class="mb-4">
                @yield('header')
            </div>
        @endif
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