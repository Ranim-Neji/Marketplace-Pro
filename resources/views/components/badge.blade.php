@props(['variant' => 'default'])

@php
    $variants = [
        'default' => 'border-border text-muted-foreground bg-muted',
        'primary' => 'border-primary text-primary-foreground bg-primary',
        'success' => 'border-primary/20 text-primary bg-primary/5',
        'warning' => 'border-accent/30 text-accent-foreground bg-accent',
        'danger' => 'border-warning/30 text-warning bg-warning/10',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-0.5 rounded-md border text-[10px] font-semibold uppercase tracking-wider shadow-sm ' . ($variants[$variant] ?? $variants['default'])]) }}>
    {{ $slot }}
</span>
