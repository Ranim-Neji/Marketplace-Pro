@props(['bestsellers'])

@if($bestsellers && $bestsellers->count() > 0)
<div class="py-24 border-t border-border bg-background/50 backdrop-blur-md overflow-hidden relative" id="bestsellers-section">
    <div class="container-layout">
        <div class="text-center mb-16 space-y-2 relative z-10">
            <h2 class="text-3xl font-bold text-foreground font-serif italic">Bestsellers</h2>
            <p class="text-muted-foreground">Most popular products this month</p>
        </div>

        {{-- Desktop 3D Carousel --}}
        <div class="hidden md:flex justify-center items-center perspective-1200 h-[500px] relative z-10" 
             x-data="carousel3d({{ $bestsellers->count() }})"
             @mouseenter="isPaused = true"
             @mouseleave="isPaused = false"
             @touchstart="isPaused = true"
             @touchend="isPaused = false">
            
            <div x-ref="carousel" class="relative w-[280px] h-[380px] preserve-3d">
                <div x-ref="items" class="absolute inset-0 preserve-3d">
                    @foreach($bestsellers as $index => $product)
                        <div class="carousel-item absolute inset-0 bg-card rounded-2xl border border-primary/20 shadow-sm hover:shadow-[0_0_25px_rgba(230,6,122,0.3)] transition-shadow duration-300 hover:scale-[1.02] group cursor-pointer overflow-hidden flex flex-col"
                             @click="window.location.href = '{{ route('products.show', $product) }}'">
                            
                            {{-- Image & Gradient --}}
                            <div class="relative flex-1 bg-muted">
                                <img src="{{ $product->image_url }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-card via-card/50 to-transparent opacity-90 group-hover:opacity-100 transition-opacity"></div>
                                
                                {{-- Wishlist Overlay --}}
                                <div class="absolute top-4 right-4 z-20" x-data="{ 
                                    inWishlist: {{ auth()->check() && auth()->user()->wishlist->contains('product_id', $product->id) ? 'true' : 'false' }},
                                    toggle() {
                                        fetch('{{ route('wishlist.toggle', $product) }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            this.inWishlist = data.in_wishlist;
                                            window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: data.message } }));
                                        });
                                    }
                                }">
                                    <button @click.stop.prevent="toggle" 
                                        class="h-10 w-10 flex items-center justify-center transition-all duration-300 hover:scale-125 group/heart">
                                        <i :class="inWishlist ? 'fa-solid text-rose-500 drop-shadow-[0_0_8px_rgba(244,63,94,0.4)]' : 'fa-regular text-white drop-shadow-md group-hover/heart:text-rose-400'" class="fa-heart text-[18px] transition-all"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Product Details --}}
                            <div class="absolute bottom-0 inset-x-0 p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                <h3 class="font-bold text-foreground truncate text-lg mb-2">{{ $product->title }}</h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-primary font-bold text-lg">${{ number_format($product->effective_price, 2) }}</span>
                                    <div class="flex items-center gap-1.5 text-xs text-[var(--chart-4)] bg-background/80 backdrop-blur px-2 py-1 rounded-full border border-border">
                                        <i class="fa-solid fa-star"></i>
                                        <span class="text-foreground font-semibold">{{ number_format($product->average_rating, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Mobile Horizontal Scroll Fallback --}}
        <div class="md:hidden flex overflow-x-auto gap-4 pb-8 snap-x snap-mandatory custom-scrollbar px-4 -mx-4 relative z-10">
            @foreach($bestsellers as $product)
                <div class="shrink-0 w-[260px] snap-center">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .perspective-1200 { perspective: 1200px; }
    .preserve-3d { transform-style: preserve-3d; }
    .carousel-item {
        backface-visibility: hidden; /* Optional: hides the back of the card entirely */
        will-change: transform, opacity;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('carousel3d', (itemCount) => ({
            rotation: 0,
            isPaused: false,
            radius: 300,
            init() {
                if (itemCount < 1) return;
                
                // Calculate optimal radius so cards don't overlap
                // Width = 280, padding = 40
                this.radius = Math.max(300, (280 / 2) / Math.tan(Math.PI / Math.max(3, itemCount))) + 40;
                
                // Pre-calculate fixed transforms
                if (this.$refs.items) {
                    Array.from(this.$refs.items.children).forEach((child, index) => {
                        const angle = (360 / itemCount) * index;
                        child.style.transform = `rotateY(${angle}deg) translateZ(${this.radius}px)`;
                    });
                }
                
                // Start animation loop
                this.animate();
            },
            animate() {
                if (!this.isPaused && this.$refs.carousel && this.$refs.items) {
                    this.rotation -= 0.15; // Speed of auto-rotation
                    
                    // Rotate the entire carousel container
                    this.$refs.carousel.style.transform = `rotateY(${this.rotation}deg)`;
                    
                    // Update opacity for each item based on its position relative to the viewer
                    Array.from(this.$refs.items.children).forEach((child, index) => {
                        const angle = (360 / itemCount) * index;
                        let absoluteAngle = (angle + this.rotation) % 360;
                        if (absoluteAngle < 0) absoluteAngle += 360;
                        
                        // Calculate distance from the front (0 degrees)
                        let dist = Math.min(absoluteAngle, 360 - absoluteAngle);
                        
                        // Fade out cards in the back
                        let opacity = Math.max(0.15, 1 - (dist / 180) * 1.2);
                        child.style.opacity = opacity;
                        
                        // Only allow clicking the cards facing forward
                        child.style.pointerEvents = opacity > 0.8 ? 'auto' : 'none';
                    });
                }
                requestAnimationFrame(() => this.animate());
            }
        }));
    });
</script>
@endpush
@endif
