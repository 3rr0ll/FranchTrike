<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Edit Operator
        </h2>
    </x-slot>

    <div class="py-6 px-4">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('operator.operators.update', $operator) }}" method="POST">
                @csrf
                @method('PUT')
                @include('operator._form', ['operator' => $operator])
                <div class="mt-4">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>