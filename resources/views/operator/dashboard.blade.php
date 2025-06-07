<x-app-layout>
    <x-slot name="header">

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button type="submit"
                class="button">
                Logout
            </x-button>
        </form>

        <h2 class="text-xl font-semibold leading-tight">
            Operator Dashbaord
        </h2>
    </x-slot>


    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('operator.create') }}">
            <x-button class="ms-4">
                Apply Fracnhise
            </x-button>
        </a>
    </div>

</x-app-layout>