<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Operator Information
        </h2>
    </x-slot>

    <div class="py-6 px-4">
        <div class="bg-black shadow rounded-lg p-6">
            <form action="{{ route('operator.store') }}" method="POST">
                @csrf
                @include('operator._form', ['operator' => null])
                <div class="mt-4">
                    <div class="flex justify-end">
                        <x-button type="submit">
                            Save
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>