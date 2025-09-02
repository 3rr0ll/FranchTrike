<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Operator') }}</title>
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
                    <a href="{{ route('operator.dashboard') }}" class="flex ml-2 md:mr-24">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">Franchtrike</span>
                    </a>
                </div>
                <div class="flex items-center">
                    {{-- Notifications bell --}}
                    <div class="relative mr-4">
                        <button type="button" data-dropdown-toggle="dropdown-notifications" class="relative text-white hover:text-primary-gold focus:outline-none" id="open-notifs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @php
                                $notifCount = Auth::user()->siteNotifications()->whereNull('read_at')->count();
                            @endphp
                            @if($notifCount > 0)
                                <span id="notif-badge" class="absolute -top-2 -right-2 inline-flex items-center justify-center w-2.5 h-2.5 bg-red-600 rounded-full"></span>
                            @endif
                        </button>
                        <div id="dropdown-notifications" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-sm shadow-lg ring-1 ring-black ring-opacity-10 z-50">
                            <div class="px-4 py-2 border-b font-semibold text-primary-navy">Notifications</div>
                            <div class="max-h-80 overflow-y-auto">
                                @php
                                    $latestNotifs = Auth::user()->siteNotifications()->latest()->take(10)->get();
                                @endphp
                                @forelse($latestNotifs as $n)
                                    <div class="px-4 py-3 border-b text-sm">
                                        <div class="text-gray-800">{{ $n->message }}</div>
                                        <div class="text-xs text-gray-500">{{ optional($n->created_at)->diffForHumans() }}</div>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-sm text-gray-500">No notifications</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button" class="flex text-sm" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img src="{{ asset('images/logo.png') }}" alt="User" class="w-10 h-10 rounded-full">
                            </button>
                        </div>
                        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-sm shadow-sm" id="dropdown-user">
                            <div class="px-4 py-3 rounded-sm cursor-pointer" role="menuitem" id="open-profile-modal">
                                <p class="text-sm text-gray-900" role="none">
                                    {{ Auth::user()->name ?? 'Operator User' }}
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate" role="none">
                                    {{ Auth::user()->email ?? 'operator@example.com' }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{ route('operator.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-navy hover:text-white" role="menuitem">Dashboard</a>
                                </li>
                                <li>
                                    <a href="{{ route('operator.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-navy hover:text-white" role="menuitem">Settings</a>
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
                    @php $isActive = request()->routeIs('operator.dashboard'); @endphp
                    <a href="{{ route('operator.dashboard') }}"
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
                    @php $isActive = request()->routeIs('operator.documents.status'); @endphp
                    <a href="{{ route('operator.documents.status') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                            <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                        </svg>
                        <span class="ms-3">Document Status</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('operator.driver.index'); @endphp
                    <a href="{{ route('operator.driver.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        <span class="ms-3">My Drivers</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('operator.franchise.index'); @endphp
                    <a href="{{ route('operator.franchise.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z" />
                            <path d="M12.5 8a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                        </svg>
                        <span class="ms-3">Franchise Applications</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('operator.payments.index'); @endphp
                    <a href="{{ route('operator.payments.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 8a7 7 0 11-14 0 7 7 0 0114 0z" />
                            <path d="M12.5 8a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                        </svg>
                        <span class="ms-3">Payments</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    {{-- Main content --}}
    <div class="p-4 sm:ml-64 mt-16">
        {{-- This will render the "header" section if defined in the child view --}}
        @hasSection('header')
            <div>
                @yield('header')
            </div>
        @endif

        @yield('content')
    </div>
    
    {{-- Profile Modal --}}
    <div id="profile-modal" class="fixed inset-0 z-[60] hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative bg-white rounded-sm shadow w-full max-w-lg mx-4">
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <h3 class="text-lg font-semibold text-primary-navy">My Profile</h3>
                <button id="close-profile-modal" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div class="p-4">
                <div class="flex items-start gap-4 mb-4">
                    <img src="{{ Auth::user()->profile_photo_url ?? asset('images/logo.png') }}" alt="Profile" class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <p class="text-lg font-semibold text-primary-navy">{{ optional(Auth::user()->operator)->full_name ?? Auth::user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-600">{{ Auth::user()->role->name ?? (Auth::user()->getRoleNames()->first() ?? 'Operator') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Contact No.</p>
                        <p class="text-gray-900">{{ optional(Auth::user()->operator)->contact_no ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="text-gray-900">{{ optional(Auth::user()->operator)->full_address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Sex</p>
                        <p class="text-gray-900">{{ optional(Auth::user()->operator)->sex ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Civil Status</p>
                        <p class="text-gray-900">{{ optional(Auth::user()->operator)->civil_status ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Birth Date</p>
                        <p class="text-gray-900">{{ optional(optional(Auth::user()->operator)->birth_date)->format('M d, Y') ?? (optional(Auth::user()->operator)->birth_date ?: '—') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Age</p>
                        <p class="text-gray-900">{{ optional(Auth::user()->operator)->age ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Member Since</p>
                        <p class="text-gray-900">{{ Auth::user()->created_at?->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('operator.settings') }}">
                        <x-button>
                            Change Password
                        </x-button>
                    </a>
                </div>
            </div>
        </div>
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

        // Profile modal open/close handlers
        document.addEventListener('DOMContentLoaded', function () {
            var openBtn = document.getElementById('open-profile-modal');
            var modal = document.getElementById('profile-modal');
            var closeBtn = document.getElementById('close-profile-modal');
            var dismissBtn = document.getElementById('dismiss-profile-modal');
            var backdrop = modal ? modal.querySelector('.absolute.inset-0') : null;
            var openNotifs = document.getElementById('open-notifs');
            var notifBadge = document.getElementById('notif-badge');

            function openModal() {
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            if (openBtn) openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (dismissBtn) dismissBtn.addEventListener('click', closeModal);
            if (backdrop) backdrop.addEventListener('click', closeModal);

            // Mark notifications as read when opening the dropdown
            if (openNotifs) {
                openNotifs.addEventListener('click', function () {
                    fetch('{{ route('operator.notifications.read') }}', {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(function() {
                        if (notifBadge) notifBadge.remove();
                    }).catch(function() {});
                });
            }
        });
    </script>
</body>
</html>