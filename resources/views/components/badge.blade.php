@props(['variant' => 'default'])

@php
    $variants = [
        'default' => 'border-border text-muted-foreground bg-muted',
        'primary' => 'border-primary text-primary-foreground bg-primary',
        'success' => 'border-primary text-primary bg-emerald-50',
        'warning' => 'border-accent text-accent bg-amber-50',
        'danger' => 'border-warning text-warning bg-red-50',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-0.5 rounded-md border text-[10px] font-semibold uppercase tracking-wider shadow-sm ' . ($variants[$variant] ?? $variants['default'])]) }}>
    {{ $slot }}
</span>
