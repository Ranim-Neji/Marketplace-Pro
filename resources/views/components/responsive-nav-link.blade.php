@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary text-start text-base font-medium text-primary bg-primary/10 focus:outline-none focus:text-primaryHover focus:bg-primary/20 focus:border-primaryHover transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-muted-foreground hover:text-foreground hover:bg-accent hover:border-border focus:outline-none focus:text-foreground focus:bg-accent focus:border-border transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
