@props(['name'])

@php
    $aliases = [
        'calendar-days' => 'calendar-alt',
        'file-certificate' => 'certificate',
        'people-group' => 'users',
        'shield-check' => 'shield-alt',
    ];
    $icon = $aliases[$name] ?? $name;
@endphp

<i {{ $attributes->merge(['class' => "fas fa-{$icon}"]) }} aria-hidden="true"></i>
