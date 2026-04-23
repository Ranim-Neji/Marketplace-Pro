@props(['variant' => 'primary', 'size' => 'md'])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-primary dark:focus:ring-offset-dark active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none rounded-lg';
    
    $variants = [
        'primary' => 'bg-primary text-primary-foreground hover:opacity-90 dark:bg-primary dark:text-primary-foreground dark:hover:opacity-90 shadow-sm',
        'secondary' => 'bg-card dark:bg-dark border border-border dark:border-border text-foreground dark:text-primary-foreground hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent dark:hover:text-accent-foreground shadow-sm',
        'ghost' => 'text-foreground dark:text-primary-foreground hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent dark:hover:text-accent-foreground',
        'danger' => 'bg-warning text-white hover:opacity-90 dark:bg-warning dark:text-white border border-warning dark:border-warning dark:hover:opacity-90',
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
