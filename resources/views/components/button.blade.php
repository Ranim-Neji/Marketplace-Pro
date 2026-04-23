@props(['variant' => 'primary', 'size' => 'md'])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none rounded-lg';
    
    $variants = [
        'primary' => 'bg-primary text-primary-foreground hover:bg-primary-hover shadow-sm',
        'secondary' => 'bg-accent text-accent-foreground hover:opacity-90 shadow-sm',
        'ghost' => 'text-foreground hover:bg-muted',
        'danger' => 'bg-warning text-warning-foreground hover:opacity-90',
        'dark' => 'bg-dark text-dark-foreground hover:opacity-90 shadow-sm',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
@endphp

<button {{ $attributes->merge(['class' => "$baseClasses " . ($variants[$variant] ?? $variants['primary']) . " " . ($sizes[$size] ?? $sizes['md'])]) }}>
    {{ $slot }}
</button>
