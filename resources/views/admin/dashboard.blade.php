<x-app-layout>
    <x-slot name="header">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button type="submit"
                class="button">
                Logout
            </x-button>
        </form>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome, {{ Auth::user()->first_name }}
        </h2>
    </x-slot>


    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <a href="{{ route('admin.operators.index') }}">
                <x-button>
                    Manage Operators
                </x-button>
            </a>

            <a href="{{ route('admin.drivers.index') }}">
                <x-button>
                    Manage Drivers
                </x-button>
            </a>

            <a href="{{ route('admin.operators.index') }}">
                <x-button>
                    Manage Operators
                </x-button>
            </a>
        </div>
    </div>
</x-app-layout>