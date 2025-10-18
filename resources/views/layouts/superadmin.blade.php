<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


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
                        class="inline-flex items-center justify-center w-10 h-10 text-white rounded-full shadow-lg sm:hidden hover:bg-primary-navy hover:text-primary-gold focus:outline-none focus:ring-2 focus:ring-primary-gold transition-all duration-200"
                        title="Open sidebar">
                        <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="sr-only">Open sidebar</span>
                    </button>
                    <a href="{{ route('superadmin.home') }}" class="flex ml-2 md:mr-24">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">Franchtrike Super Admin</span>
                    </a>
                </div>
                {{-- Superadmin quick info as modal trigger --}}
                <button id="open-profile-modal" type="button"
                    class="flex items-center bg-white rounded shadow px-2 py-1.5 sm:px-3 sm:py-1.5 mr-2 sm:mr-4 focus:outline-none focus:ring-2 focus:ring-primary-gold transition-all min-w-[120px] sm:min-w-[0]"
                    aria-label="Open profile modal">
                    <svg class="w-7 h-7 text-primary-navy mr-2 hidden sm:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 1115 0v.25a.25.25 0 01-.25.25H4.75a.25.25 0 01-.25-.25v-.25z" />
                    </svg>
                    <div class="flex flex-col leading-tight text-left">
                        <span class="font-semibold text-primary-navy text-xs sm:text-sm break-words">
                            {{ Auth::user()->name ?? 'Superadmin' }}
                        </span>
                    </div>
                    <svg class="w-4 h-4 text-primary-navy ml-2 sm:ml-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

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
                                    <p class="text-lg font-semibold text-primary-navy">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                    <p class="text-sm text-gray-600">{{ Auth::user()->role->name ?? (Auth::user()->getRoleNames()->first() ?? 'Superadmin') }}</p>
                                </div>
                            </div>
                            <div class="ml-2">
                                <p class="text-sm text-gray-500">Member Since</p>
                                <p class="text-gray-900">{{ Auth::user()->created_at?->format('M d, Y') }}</p>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <a href="{{ route('superadmin.settings') }}">
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
            </div>
        </div>
    </nav>

    {{-- Sidebar --}}
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-primary-navy border-r border-primary-gold sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 flex flex-col justify-between overflow-y-auto bg-primary-navy">
            <ul class="space-y-2 font-medium">
                <li>
                    @php $isActive = request()->routeIs('superadmin.dashboard'); @endphp
                    <a href="{{ route('superadmin.dashboard') }}"
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
                    @php $isActive = request()->routeIs('superadmin.users.*'); @endphp
                    <a href="{{ route('superadmin.users.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                        {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="ms-3">User Management</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('superadmin.payments.*'); @endphp
                    <a href="{{ route('superadmin.payments.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                        {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="ms-3">Payment Management</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('superadmin.franchise.*'); @endphp
                    <a href="{{ route('superadmin.franchise.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                        {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ $isActive ? 'text-primary-navy' : 'text-white group-hover:text-primary-navy' }}"
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                        </svg>
                        <span class="ms-3">Franchise List</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs('superadmin.activity.index'); @endphp
                    <a href="{{ route('superadmin.activity.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                        {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                          </svg>                          
                        <span class="ms-3">Activity Log</span>
                    </a>
                </li>
                <li>
                    @php $isActive = request()->routeIs(patterns: 'superadmin.database.*') || request()->routeIs('superadmin.database.index') || request()->routeIs('superadmin.database.show'); @endphp

                    <a href="{{ route('superadmin.database.index') }}"
                        class="flex items-center p-2 rounded-lg group 
                        {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-white hover:text-primary-navy' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3C7.03 3 3 4.79 3 7.02v9.96C3 19.2 7.03 21 12 21s9-1.8 9-4.02V7.02C21 4.79 16.97 3 12 3Zm0 1.5c4.14 0 7.5 1.46 7.5 2.52s-3.36 2.52-7.5 2.52-7.5-1.46-7.5-2.52S7.86 4.5 12 4.5Zm7.5 7.02c0 1.06-3.36 2.52-7.5 2.52s-7.5-1.46-7.5-2.52v-1.77c1.72 1.07 4.77 1.77 7.5 1.77s5.78-.7 7.5-1.77v1.77Zm0 3.48c0 1.07-3.36 2.52-7.5 2.52s-7.5-1.45-7.5-2.52v-1.77c1.72 1.08 4.77 1.77 7.5 1.77s5.78-.69 7.5-1.77v1.77Zm0 3.48c0 1.06-3.36 2.52-7.5 2.52s-7.5-1.46-7.5-2.52v-1.77c1.72 1.07 4.77 1.77 7.5 1.77s5.78-.7 7.5-1.77v1.77Z" />
                        </svg>
                        <span class="ms-3">Tables</span>
                    </a>
                </li>
            </ul>
            <ul class="space-y-2 font-medium mt-4">
                <li>
                    @php $isActive = request()->routeIs('superadmin.settings'); @endphp
                    <a href="{{ route('superadmin.settings') }}"
                        class="flex items-center p-2 gap-2 rounded-lg group {{ $isActive ? 'bg-white text-primary-navy' : 'text-white hover:bg-primary-gold hover:text-primary-navy' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="ms-3">Settings</span>
                    </a>
                </li>
                <li>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" class="flex items-center w-full text-left p-2 rounded-lg text-white hover:bg-accent-red hover:text-white" onclick="confirmLogout(event)">
                            <svg class="w-5 h-5 mr-2 text-white group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 1 1-4 0v-1m0-8V5a2 2 0 1 1 4 0v1" />
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
        @hasSection('header')
        <div>
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


        // Profile modal open/close handlers
        document.addEventListener('DOMContentLoaded', function() {
            var openBtn = document.getElementById('open-profile-modal');
            var modal = document.getElementById('profile-modal');
            var closeBtn = document.getElementById('close-profile-modal');
            var backdrop = modal ? modal.querySelector('.absolute.inset-0') : null;

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
            if (backdrop) backdrop.addEventListener('click', closeModal);
        });
    </script>
</body>

</html>