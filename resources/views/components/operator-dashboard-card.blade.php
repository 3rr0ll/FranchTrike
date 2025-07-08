@props([
    'icon',
    'title',
    'description',
    'route',
    'color' => 'text-blue-500',
    'buttonText' => 'Open'
])

<div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
    <div class="mb-4">
        <svg class="w-10 h-10 {{ $color }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    </div>
    <h3 class="text-lg font-semibold mb-2">{{ $title }}</h3>
    <p class="text-gray-500 text-sm mb-4 text-center">{{ $description }}</p>
    <a href="{{ $route }}">
        <x-button>
            {{ $buttonText }}
        </x-button>
    </a>
</div>
