@extends('layouts.app')

@section('content')
<div class="relative overflow-hidden">
    {{-- SaaS Hero --}}
    <div class="relative pt-24 pb-32 lg:pt-32 lg:pb-48 flex items-center justify-center">
        <div class="container-layout relative z-10">
            <div class="text-center max-w-4xl mx-auto space-y-8">
                <x-badge variant="primary" class="animate-fade-in inline-flex">Marketplace v1.0</x-badge>
                
                <h1 class="text-5xl lg:text-7xl font-bold tracking-tight text-foreground leading-[1.1] animate-fade-in font-serif italic" style="animation-delay: 100ms">
                    Buy and sell products with <span class="text-primary not-italic font-sans">unmatched speed.</span>
                </h1>
                
                <p class="text-lg lg:text-xl text-muted-foreground max-w-2xl mx-auto leading-relaxed animate-fade-in" style="animation-delay: 200ms">
                    The modern marketplace for high-end digital and physical products. Optimized for the next generation of commerce.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in" style="animation-delay: 300ms">
                    <a href="{{ route('catalog.index') }}">
                        <x-button size="lg">Explore Catalogue</x-button>
                    </a>
                    <a href="{{ route('register') }}">
                        <x-button variant="secondary" size="lg">Become a Seller</x-button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bestsellers 3D Carousel --}}
    <x-bestsellers-carousel :bestsellers="$bestsellers" />

    {{-- Personalized Recommendations (Auth Only) --}}
    @auth
    @if(isset($recommendedProducts) && $recommendedProducts->isNotEmpty())
    <section class="py-24 bg-muted/50 border-t border-border backdrop-blur-sm">
        <div class="container-layout">
            <div class="mb-16 text-center space-y-2">
                <h2 class="text-3xl font-bold text-foreground">Recommended for You</h2>
                <p class="text-muted-foreground">Tailored suggestions based on your behavior.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($recommendedProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endauth

    {{-- Value Props --}}
    <section class="py-24 border-t border-border bg-background/80 backdrop-blur-md">
        <div class="container-layout">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4">
                    <div class="h-12 w-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center border border-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-foreground">Secure Transactions</h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">Every purchase is protected by our advanced security protocols and dispute system.</p>
                </div>
                <div class="space-y-4">
                    <div class="h-12 w-12 bg-chart-2/10 text-[var(--chart-2)] rounded-xl flex items-center justify-center border border-[var(--chart-2)]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-foreground">Fast Delivery</h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">Our sellers are committed to lightning-fast shipping and instant digital deliveries.</p>
                </div>
                <div class="space-y-4">
                    <div class="h-12 w-12 bg-chart-3/10 text-[var(--chart-3)] rounded-xl flex items-center justify-center border border-[var(--chart-3)]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-foreground">Verified Quality</h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">We manually vet our top sellers to ensure you only get the highest quality products.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
