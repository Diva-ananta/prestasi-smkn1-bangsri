@props(['items' => []])

<nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm text-gray-600">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">
                <i class="fas fa-home"></i>
            </a>
        </li>
        @foreach ($items as $label => $url)
            <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
            @if ($loop->last)
                <li class="font-semibold text-gray-800">{{ $label }}</li>
            @else
                <li><a href="{{ $url }}" class="hover:text-blue-600">{{ $label }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>