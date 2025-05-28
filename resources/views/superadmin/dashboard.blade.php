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
       
</x-app-layout>
