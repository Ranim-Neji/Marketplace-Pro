@props(['product'])

<div class="group relative flex flex-col bg-card border border-border rounded-xl overflow-hidden shadow-sm hover:shadow-premium hover:-translate-y-1 hover:ring-2 hover:ring-ring transition-all duration-300">
    {{-- Image --}}
    <div class="relative aspect-[4/5] bg-muted border-b border-border overflow-hidden">
        <img src="{{ $product->image_url }}" 
             alt="{{ $product->title }}" 
             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        
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
            <button @click.prevent="toggle" 
                class="h-10 w-10 flex items-center justify-center transition-all duration-300 hover:scale-125 group/heart">
                <i :class="inWishlist ? 'fa-solid text-rose-500 drop-shadow-[0_0_8px_rgba(244,63,94,0.4)]' : 'fa-regular text-white drop-shadow-md group-hover/heart:text-rose-400'" class="fa-heart text-[18px] transition-all"></i>
            </button>
        </div>

        {{-- Badge --}}
        @if($product->is_featured)
            <div class="absolute top-4 left-4">
                <x-badge variant="primary">Featured</x-badge>
            </div>
        @endif
    </div>

    {{-- Meta --}}
    <div class="p-5 space-y-4 flex-grow flex flex-col">
        <div class="flex-grow">
            <p class="text-[11px] font-bold uppercase tracking-widest text-muted-foreground mb-1">
                {{ $product->categories->first()->name ?? 'Uncategorized' }}
            </p>
            <a href="{{ route('products.show', $product) }}" class="block">
                <h3 class="text-sm font-semibold text-foreground hover:text-primary transition-colors line-clamp-2">
                    {{ $product->title }}
                </h3>
            </a>
        </div>
        
        <div class="flex items-center justify-between pt-4 border-t border-border">
            <span class="text-base font-bold text-foreground font-sans">
                ${{ number_format($product->effective_price, 2) }}
            </span>
            
            @auth
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-semibold text-primary hover:underline transition-all">
                    Add to Cart
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="text-xs font-semibold text-primary hover:underline transition-all">Login</a>
            @endauth
        </div>
    </div>
</div>
