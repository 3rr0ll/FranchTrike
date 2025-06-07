<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Operator List</h2>

        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Contact</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($operators as $operator)
                <tr>
                    <td class="px-4 py-2 border">{{ $operator->first_name }} {{ $operator->last_name }}</td>
                    <td class="px-4 py-2 border">{{ $operator->contact_no }}</td>
                    <td class="px-4 py-2 border">
                        <a href="{{ route('admin.operators.show', $operator->id) }}"
                            class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>