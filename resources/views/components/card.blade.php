@props(['variant' => 'default', 'padding' => 'p-6'])

@php
    $baseClasses = 'rounded-xl transition-all duration-300';
    $variants = [
        'default' => 'bg-card border border-border shadow-premium',
        'glass' => 'bg-card/90 backdrop-blur-md border border-border shadow-premium',
        'flat' => 'bg-muted border border-border',
    ];
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses " . ($variants[$variant] ?? $variants['default']) . " $padding"]) }}>
    {{ $slot }}
</div>
