@extends('layouts.app')
@section('title', 'Cart | MarketPlace')

@section('content')
<div class="container-layout py-12 lg:py-16">
    <div class="flex items-baseline gap-6 mb-12 border-b border-border pb-8">
        <h1 class="text-3xl font-semibold text-foreground tracking-tight font-serif italic">Your Cart</h1>
        <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest font-mono">{{ $cart->item_count }} Items</div>
    </div>

    @if($cart->items->isEmpty())
        <div class="py-32 flex flex-col items-center justify-center bg-card rounded-2xl border border-border shadow-sm animate-fade-in">
            <div class="h-20 w-20 bg-muted rounded-full flex items-center justify-center mb-6">
                <i class="fa-solid fa-cart-shopping text-3xl text-muted-foreground"></i>
            </div>
            <h2 class="text-xl font-semibold text-foreground mb-2">Your cart is empty</h2>
            <p class="text-sm text-muted-foreground mb-8">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('catalog.index') }}">
                <x-button size="lg" class="px-8">Browse Catalogue</x-button>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">
            {{-- Staged Assets --}}
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-card rounded-2xl border border-border shadow-premium overflow-hidden">
                    <div class="px-8 py-4 border-b border-border flex justify-between items-center bg-muted/50">
                        <span class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest font-mono">Products ({{ $cart->item_count }})</span>
                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf @method('DELETE')
                            <button class="text-[11px] font-bold text-warning hover:text-warning uppercase tracking-widest hover:underline transition-colors font-mono">
                                Clear Cart
                            </button>
                        </form>
                    </div>

                    <div class="divide-y divide-border">
                        @foreach($cart->items as $item)
                            <div class="p-6 sm:px-8 hover:bg-muted/30 transition-colors group">
                                <div class="flex flex-col sm:flex-row items-center gap-6">
                                    {{-- Asset Thumbnail --}}
                                    <a href="{{ route('products.show', $item->product) }}" class="shrink-0">
                                        <div class="h-20 w-20 rounded-xl overflow-hidden bg-muted border border-border group-hover:border-primary transition-colors">
                                            <img src="{{ $item->product->image_url }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </div>
                                    </a>

                                    {{-- Specification --}}
                                    <div class="flex-grow min-w-0 text-center sm:text-left">
                                        <a href="{{ route('products.show', $item->product) }}"
                                           class="text-sm font-semibold text-foreground truncate block hover:text-primary transition-colors">
                                            {{ $item->product->title }}
                                        </a>
                                        <div class="text-[10px] font-medium text-muted-foreground uppercase tracking-widest mt-1">
                                            @foreach($item->product->categories->take(2) as $cat)
                                                {{ $cat->name }}@if(!$loop->last) / @endif
                                            @endforeach
                                        </div>
                                        <div class="text-sm font-bold text-foreground mt-2">${{ number_format($item->price, 2) }}</div>
                                    </div>

                                    {{-- Volume Controller --}}
                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center bg-muted rounded-lg border border-border p-1">
                                        @csrf @method('PATCH')
                                        <button type="button" class="h-8 w-8 flex items-center justify-center text-muted-foreground hover:text-foreground transition-colors"
                                                onclick="this.nextElementSibling.stepDown(); this.form.submit()"><i class="fa-solid fa-minus text-[10px]"></i></button>
                                        <input type="number" name="quantity"
                                               class="w-10 bg-transparent text-center font-semibold text-foreground text-sm outline-none"
                                               value="{{ $item->quantity }}"
                                               min="1" max="99"
                                               onchange="this.form.submit()">
                                        <button type="button" class="h-8 w-8 flex items-center justify-center text-muted-foreground hover:text-foreground transition-colors"
                                                onclick="this.previousElementSibling.stepUp(); this.form.submit()"><i class="fa-solid fa-plus text-[10px]"></i></button>
                                    </form>

                                    {{-- Liquidity --}}
                                    <div class="text-right sm:min-w-[100px]">
                                        <div class="text-base font-bold text-foreground">${{ number_format($item->subtotal, 2) }}</div>
                                    </div>

                                    {{-- Purge --}}
                                    <form method="POST" action="{{ route('cart.remove', $item) }}">
                                        @csrf @method('DELETE')
                                        <button class="h-8 w-8 flex items-center justify-center rounded-md text-muted-foreground hover:text-warning hover:bg-red-50 transition-colors">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground hover:text-foreground transition-colors mt-4">
                    <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                </a>
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-card rounded-2xl border border-border shadow-premium overflow-hidden p-8">
                        <h3 class="text-sm font-semibold text-foreground mb-6 flex items-center gap-2">
                            Order Summary
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-muted-foreground">Subtotal</span>
                                <span class="font-semibold text-foreground">${{ number_format($cart->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-muted-foreground">Tax (19%)</span>
                                <span class="font-semibold text-foreground">${{ number_format($cart->total * 0.19, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-muted-foreground">Shipping</span>
                                <span class="font-semibold {{ $cart->total > 100 ? 'text-primary' : 'text-foreground' }}">
                                    {{ $cart->total > 100 ? 'Free' : '$7.99' }}
                                </span>
                            </div>
                            
                            @if($cart->total <= 100)
                                <div class="p-3 rounded-lg bg-primary/5 border border-primary/20 text-[11px] font-medium text-primary">
                                    <i class="fa-solid fa-bolt mr-1"></i> Add ${{ number_format(100 - $cart->total, 2) }} more for free shipping.
                                </div>
                            @endif

                            <div class="h-px bg-border my-6"></div>

                            <div class="flex justify-between items-end">
                                <span class="text-sm font-semibold text-foreground">Total</span>
                                <span class="text-2xl font-bold text-foreground">
                                    ${{ number_format($cart->total + ($cart->total * 0.19) + ($cart->total > 100 ? 0 : 7.99), 2) }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('orders.checkout') }}" class="mt-8 w-full inline-flex items-center justify-center px-4 py-3 bg-primary text-primary-foreground font-semibold rounded-lg hover:opacity-90 transition-opacity shadow-sm active:scale-[0.98]">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
