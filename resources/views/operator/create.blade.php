<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Add Operator
        </h2>
    </x-slot>

    <div class="py-6 px-4">
        <div class="bg-black shadow rounded-lg p-6">
            <form action="{{ route('operator.store') }}" method="POST">
                @csrf
                @include('operator._form', ['operator' => null])
                <div class="mt-4">
                    <button type="submit"
                        class="bg-black-600 text-black px-4 py-2 rounded hover:bg-green-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>