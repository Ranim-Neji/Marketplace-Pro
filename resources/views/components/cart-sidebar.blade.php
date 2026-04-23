@auth
@php
    $cart = auth()->user()->cart;
    $cartItems = $cart ? $cart->items : collect();
    $cartTotal = $cart ? $cart->total : 0;
    $itemCount = $cartItems->sum('quantity');
@endphp

<div x-data="{ 
        open: {{ session('open_cart') ? 'true' : 'false' }},
        init() {
            this.$watch('open', value => {
                if(value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
            
            // If opened via session, ensure body overflow is set correctly on load
            if (this.open) {
                document.body.style.overflow = 'hidden';
            }
        }
     }" 
     @keydown.window.escape="open = false"
     @open-cart.window="open = true"
     class="relative z-[150]" 
     style="display: none;"
     x-show="open">
    
    {{-- Backdrop --}}
    <div x-show="open" 
         x-transition.opacity.duration.300ms 
         @click="open = false"
         class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity"></div>

    {{-- Sidebar Panel --}}
    <div x-show="open" 
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 z-10 w-full max-w-md bg-card border-l border-border shadow-premium flex flex-col h-full">
        
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-border shrink-0">
            <h2 class="text-xl font-semibold text-foreground tracking-tight font-serif italic">Your Cart <span class="text-xs ml-2 text-muted-foreground not-italic font-mono uppercase font-bold">{{ $itemCount }} items</span></h2>
            <button @click="open = false" class="text-muted-foreground hover:text-foreground transition-colors p-2 rounded-md hover:bg-accent">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            @if($cartItems->isEmpty())
                <div class="h-full flex flex-col items-center justify-center text-center space-y-4">
                    <div class="h-16 w-16 bg-muted rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-muted-foreground"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                    </div>
                    <p class="text-sm font-medium text-muted-foreground">Your cart is empty</p>
                    <button @click="open = false" class="text-xs font-bold text-primary hover:underline">Continue Shopping</button>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($cartItems as $item)
                        <div class="flex gap-4">
                            <a href="{{ route('products.show', $item->product) }}" class="shrink-0 h-20 w-20 rounded-lg overflow-hidden border border-border">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->title }}" class="h-full w-full object-cover">
                            </a>
                            <div class="flex flex-col flex-1 min-w-0">
                                <div class="flex justify-between gap-2 items-start">
                                    <a href="{{ route('products.show', $item->product) }}" class="text-sm font-semibold text-foreground hover:text-primary truncate transition-colors">{{ $item->product->title }}</a>
                                    <form method="POST" action="{{ route('cart.remove', $item) }}" class="shrink-0">
                                        @csrf @method('DELETE')
                                        <button class="text-muted-foreground hover:text-warning transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="text-xs text-muted-foreground mt-0.5 uppercase font-mono tracking-widest">${{ number_format($item->price, 2) }} x {{ $item->quantity }}</div>
                                
                                <div class="flex justify-between items-center mt-auto pt-2">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center bg-muted rounded border border-border px-1 py-0.5">
                                        @csrf @method('PATCH')
                                        <button type="button" class="px-1 text-muted-foreground hover:text-foreground" onclick="this.nextElementSibling.stepDown(); this.form.submit()">-</button>
                                        <input type="number" name="quantity" class="w-8 bg-transparent text-center text-xs font-semibold text-foreground outline-none p-0 border-0" value="{{ $item->quantity }}" min="1" max="99" onchange="this.form.submit()">
                                        <button type="button" class="px-1 text-muted-foreground hover:text-foreground" onclick="this.previousElementSibling.stepUp(); this.form.submit()">+</button>
                                    </form>
                                    <div class="text-sm font-bold text-foreground">${{ number_format($item->subtotal, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer Summary --}}
        @if(!$cartItems->isEmpty())
            <div class="p-6 border-t border-border bg-muted/30 shrink-0 space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-muted-foreground">Subtotal</span>
                    <span class="font-semibold text-foreground">${{ number_format($cartTotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-muted-foreground">Shipping</span>
                    <span class="font-semibold {{ $cartTotal > 100 ? 'text-primary' : 'text-foreground' }}">
                        {{ $cartTotal > 100 ? 'Free' : '$7.99' }}
                    </span>
                </div>
                <div class="h-px bg-border w-full"></div>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-foreground">Total</span>
                    <span class="text-xl font-bold text-foreground">${{ number_format($cartTotal + ($cartTotal > 100 ? 0 : 7.99), 2) }}</span>
                </div>

                <div class="pt-2 flex flex-col gap-3">
                    <a href="{{ route('orders.checkout') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-primary text-primary-foreground text-sm font-semibold rounded-lg hover:opacity-90 transition-opacity shadow-sm active:scale-[0.98]">
                        Checkout
                    </a>
                    <a href="{{ route('cart.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-transparent text-foreground text-sm font-medium rounded-lg hover:bg-accent transition-colors">
                        View Full Cart
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endauth
