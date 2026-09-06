@props(['size' => 'h-10', 'showText' => true, 'text' => 'SMK N 1 Bangsri'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <img src="{{ asset('images/logo-smk.png') }}" alt="{{ $text }}" class="{{ $size }} w-auto">
    @if($showText)
        <span class="font-bold text-gray-800 dark:text-white {{ $size == 'h-8' ? 'text-sm' : 'text-lg' }}">
            {{ $text }}
        </span>
    @endif
</div>