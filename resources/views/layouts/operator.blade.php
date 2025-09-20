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
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">Franchtrike Operator</span>
                    </a>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">

                    {{-- Operator quick info as modal trigger --}}
                    <button id="open-profile-modal" type="button"
                        class="flex items-center bg-white rounded shadow px-2 py-1.5 sm:px-3 sm:py-1.5 mr-2 sm:mr-4 focus:outline-none focus:ring-2 focus:ring-primary-gold transition-all min-w-[120px] sm:min-w-[0]"
                        aria-label="Open profile modal">
                        <svg class="w-7 h-7 text-primary-navy mr-2 hidden sm:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 1115 0v.25a.25.25 0 01-.25.25H4.75a.25.25 0 01-.25-.25v-.25z" />
                        </svg>
                        <div class="flex flex-col leading-tight text-left">
                            <span class="font-semibold text-primary-navy text-xs sm:text-sm break-words">
                                {{
                                    Auth::user()->operator
                                        ? trim(
                                            Auth::user()->operator->first_name .
                                            ' ' .
                                            (Auth::user()->operator->middle_initial ? Auth::user()->operator->middle_initial . ' ' : '') .
                                            Auth::user()->operator->last_name
                                        )
                                        : 'Operator'
                                }}
                            </span>
                        </div>
                        <svg class="w-4 h-4 text-primary-navy ml-2 sm:ml-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Notifications bell --}}
                    <div class="relative mr-2 sm:mr-4">
                        <button type="button" data-dropdown-toggle="dropdown-notifications" class="relative text-white hover:text-primary-gold focus:outline-none" id="open-notifs" aria-label="Open notifications">
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
                        <div id="dropdown-notifications" class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-sm shadow-lg ring-1 ring-black ring-opacity-10 z-50">
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
                </div>
            </div>
        </div>
    </nav>


                  

    {{-- Sidebar --}}
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-primary-navy border-r border-primary-gold sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 flex flex-col justify-between overflow-y-auto bg-primary-navy">
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
                        <span class="ms-3">Home</span>
                    </a>
                </li>
                
                <li>
                    @php $isActive = request()->routeIs('operator.documents.status'); @endphp
                    <a href="{{ route('operator.documents.status') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                        <span class="ms-3">Document Status</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('operator.driver.index'); @endphp
                    <a href="{{ route('operator.driver.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="ms-3">My Drivers</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('operator.franchise.index'); @endphp
                    <a href="{{ route('operator.franchise.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                </svg>

                        <span class="ms-3">Franchise Applications</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('operator.payments.index'); @endphp
                    <a href="{{ route('operator.payments.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                        <span class="ms-3">Payments</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('operator.motor-change.index'); @endphp
                    <a href="{{ route('operator.motor-change.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                    {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V6.75A2.25 2.25 0 0 0 14.25 4.5h-4.5A2.25 2.25 0 0 0 7.5 6.75v1.5m9 0v8.25A2.25 2.25 0 0 1 14.25 18.75h-4.5A2.25 2.25 0 0 1 7.5 16.5V8.25m9 0h-9" />
                        </svg>
                        <span class="ms-3">Motor Change Requests</span>
                    </a>
                </li>
            </ul>
            {{-- Settings and Sign Out at the bottom --}}
            <ul class="space-y-2 font-medium mt-4">
                <li>
                    <a href="{{ route('operator.edit') }}" class="flex items-center p-2 gap-2 rounded-lg group text-white hover:bg-primary-gold hover:text-primary-navy">
                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" class="flex items-center w-full text-left p-2 rounded-lg text-white hover:bg-accent-red hover:text-white" onclick="confirmLogout(event)">
                            <svg class="w-5 h-5 mr-2 text-white group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 1 1-4 0v-1m0-8V5a2 2 0 1 1 4 0v1"/>
                            </svg>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>
    {{-- Main content --}}
    <div class="p-4 sm:ml-64 mt-16 bg-[#e5e5e4]" style="min-height: 100vh;">
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
                    <img src="{{ Auth::user()->cloudinary_profile_photo_id ? 'https://res.cloudinary.com/' . config('cloudinary.cloud_name') . '/image/upload/' . Auth::user()->cloudinary_profile_photo_id : (Auth::user()->profile_photo_url ?? asset('images/logo.png')) }}" alt="Profile" class="w-16 h-16 rounded-full object-cover">
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
                    <a href="{{ route('operator.edit') }}">
                        <x-button>
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Settings
                            </span>
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