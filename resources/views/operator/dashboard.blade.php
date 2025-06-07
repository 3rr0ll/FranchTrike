<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Operator Dashbaord
        </h2>
    </x-slot>

    <div class="py-6 px-4">
        <div class="mb-4">
            <a href="{{ route('operator.create') }}">
                <x-button class="ms-4">
                    Apply Fracnhise
                </x-button>
            </a>
        </div>

</x-app-layout>