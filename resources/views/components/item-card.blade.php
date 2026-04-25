@props(['item', 'type' => 'product'])

@php
    $isProduct = $type === 'product';
    $url = $isProduct ? route('products.show', $item) : route('services.show', $item);
    $title = $isProduct ? $item->title : $item->name;
    $price = $isProduct ? $item->effective_price : $item->price;
    $categoryName = $isProduct 
        ? ($item->categories->first()->name ?? 'Uncategorized') 
        : ($item->category->name ?? 'Service');
    $isFeatured = $isProduct ? $item->is_featured : false;
    $availability = $isProduct ? $item->isInStock() : $item->availability;
@endphp

<div class="group relative flex flex-col bg-card border border-border rounded-xl overflow-hidden shadow-sm hover:shadow-premium hover:-translate-y-1 hover:ring-2 hover:ring-ring transition-all duration-300">
    {{-- Image --}}
    <div class="relative aspect-[4/5] bg-muted border-b border-border overflow-hidden">
        <img src="{{ $item->image_url }}" 
             alt="{{ $title }}" 
             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        
        {{-- Wishlist Overlay (Products only for now) --}}
        @if($isProduct)
        <div class="absolute top-4 right-4 z-20" x-data="{ 
            inWishlist: {{ auth()->check() && auth()->user()->wishlist->contains('product_id', $item->id) ? 'true' : 'false' }},
            toggle() {
                fetch('{{ route('wishlist.toggle', $item) }}', {
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
                <i :class="inWishlist ? 'fa-solid text-warning drop-shadow-[0_0_8px_rgba(232,103,92,0.4)]' : 'fa-regular text-white drop-shadow-md group-hover/heart:text-warning/70'" class="fa-heart text-[18px] transition-all"></i>
            </button>
        </div>
        @else
        {{-- Service Badge --}}
        <div class="absolute top-4 right-4 z-20">
            <span class="px-2 py-1 bg-primary/20 backdrop-blur-md border border-primary/30 text-[9px] font-black uppercase tracking-widest text-primary rounded-md">
                Service
            </span>
        </div>
        @endif

        {{-- Featured Badge --}}
        @if($isFeatured)
            <div class="absolute top-4 left-4">
                <x-badge variant="primary">Featured</x-badge>
            </div>
        @endif

        {{-- Availability Overlay --}}
        @if(!$availability)
            <div class="absolute inset-0 bg-background/60 backdrop-blur-[2px] flex items-center justify-center">
                <span class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full">
                    {{ $isProduct ? 'Out of Stock' : 'Unavailable' }}
                </span>
            </div>
        @endif
    </div>

    {{-- Meta --}}
    <div class="p-5 space-y-4 flex-grow flex flex-col">
        <div class="flex-grow">
            <p class="text-[11px] font-bold uppercase tracking-widest text-muted-foreground mb-1">
                {{ $categoryName }}
            </p>
            <a href="{{ $url }}" class="block">
                <h3 class="text-sm font-semibold text-foreground hover:text-primary transition-colors line-clamp-2">
                    {{ $title }}
                </h3>
            </a>
        </div>
        
        <div class="flex items-center justify-between pt-4 border-t border-border">
            <div class="flex flex-col">
                @if(!$isProduct)
                    <span class="text-[9px] font-bold text-muted-foreground uppercase tracking-tighter">Starting at</span>
                @endif
                <span class="text-base font-bold text-foreground font-sans">
                    ${{ number_format($price, 2) }}
                </span>
            </div>
            
            @if($isProduct)
                @auth
                <form action="{{ route('cart.add', $item) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-primary hover:underline transition-all">
                        Add to Cart
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="text-xs font-semibold text-primary hover:underline transition-all">Login</a>
                @endauth
            @else
                <a href="{{ $url }}" class="text-xs font-semibold text-primary hover:underline transition-all">Details</a>
            @endif
        </div>
    </div>
</div>
