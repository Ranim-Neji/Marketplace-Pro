@props(['variant' => 'primary', 'size' => 'md'])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 dark:focus:ring-white dark:focus:ring-offset-zinc-950 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none rounded-lg';
    
    $variants = [
        'primary' => 'bg-slate-900 text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-zinc-200 shadow-sm',
        'secondary' => 'bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 shadow-sm',
        'ghost' => 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800',
        'danger' => 'bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 border border-red-200 dark:border-red-900/30',
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
