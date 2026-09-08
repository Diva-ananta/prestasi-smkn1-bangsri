@props(['title', 'value', 'icon', 'color' => 'blue'])

@php
    $colors = [
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'yellow' => 'bg-yellow-500',
        'red' => 'bg-red-500',
        'purple' => 'bg-purple-500',
        'indigo' => 'bg-indigo-500',
    ];
@endphp

<div class="bg-white rounded-lg shadow p-6 flex items-center">
    <div class="rounded-full {{ $colors[$color] ?? 'bg-blue-500' }} p-3 text-white mr-4">
        <i class="fas fa-{{ $icon }} text-xl"></i>
    </div>
    <div>
        <p class="text-gray-500 text-sm uppercase tracking-wide">{{ $title }}</p>
        <p class="text-2xl font-bold">{{ $value }}</p>
    </div>
</div>