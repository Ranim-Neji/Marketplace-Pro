@extends('layouts.app')
@section('title', 'Checkout | MarketPlace')

@section('content')
<div class="container-layout py-12 lg:py-16">
    <div class="flex items-baseline gap-6 mb-12 border-b border-border pb-8">
        <h1 class="text-3xl font-semibold text-foreground tracking-tight font-serif italic">Checkout</h1>
        <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest font-mono">Final Step</div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">
        {{-- Form --}}
        <div class="lg:col-span-7">
            <form method="POST" action="{{ route('orders.store') }}" id="checkoutForm" class="space-y-8">
                @csrf

                {{-- Shipping --}}
                <div class="bg-card rounded-2xl border border-border p-8 shadow-premium">
                    <h3 class="text-sm font-semibold text-foreground mb-6 flex items-center gap-2">
                        <div class="h-6 w-6 rounded-md bg-primary/10 text-primary flex items-center justify-center"><i class="fa-solid fa-location-dot text-xs"></i></div>
                        Shipping Details
                    </h3>
                    
                    @if(auth()->user()->address)
                        <div class="mb-6 p-4 rounded-xl bg-muted border border-border flex justify-between items-start group transition-colors">
                            <div class="min-w-0">
                                <div class="text-[11px] font-bold text-muted-foreground uppercase tracking-widest mb-1 font-mono">Saved Address</div>
                                <div class="text-sm font-medium text-foreground line-clamp-2">{{ auth()->user()->address }}</div>
                            </div>
                            <button type="button" class="text-[11px] font-bold text-primary uppercase tracking-widest hover:underline shrink-0"
                                    onclick="document.getElementById('shippingAddress').value='{{ auth()->user()->address }}'">
                                Use Saved
                            </button>
                        </div>
                    @endif

                    <textarea name="shipping_address"
                              id="shippingAddress"
                              class="input-base h-28 @error('shipping_address') border-warning @enderror"
                              placeholder="Full shipping address..."
                              required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                    @error('shipping_address')
                        <div class="text-warning text-[11px] font-medium mt-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Payment --}}
                <div class="bg-card rounded-2xl border border-border p-8 shadow-premium">
                    <h3 class="text-sm font-semibold text-foreground mb-6 flex items-center gap-2">
                        <div class="h-6 w-6 rounded-md bg-[var(--chart-2)]/10 text-[var(--chart-2)] flex items-center justify-center"><i class="fa-solid fa-credit-card text-xs"></i></div>
                        Payment Method
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="payment-option relative block cursor-pointer group" id="cod-label">
                            <input type="radio" name="payment_method" value="cash_on_delivery"
                                   class="hidden" checked onchange="updatePaymentUI()">
                            <div class="h-full p-6 rounded-xl border-2 border-border bg-muted/50 transition-all group-hover:border-primary/30 flex flex-col items-center text-center">
                                <i class="fa-solid fa-money-bill-transfer text-2xl text-foreground mb-4 opacity-50 group-hover:opacity-100 transition-opacity"></i>
                                <div class="text-sm font-semibold text-foreground mb-1">Cash on Delivery</div>
                                <div class="text-[11px] font-medium text-muted-foreground">Pay when you receive</div>
                            </div>
                            <div class="absolute top-4 right-4 h-4 w-4 rounded-full border border-border flex items-center justify-center check-mark">
                                <div class="h-2 w-2 rounded-full bg-primary opacity-0 transition-opacity"></div>
                            </div>
                        </label>

                        <label class="payment-option relative block cursor-pointer group" id="card-label">
                            <input type="radio" name="payment_method" value="credit_card"
                                   class="hidden" onchange="updatePaymentUI()">
                            <div class="h-full p-6 rounded-xl border-2 border-border bg-muted/50 transition-all group-hover:border-[var(--chart-2)]/30 flex flex-col items-center text-center">
                                <i class="fa-solid fa-shield-halved text-2xl text-foreground mb-4 opacity-50 group-hover:opacity-100 transition-opacity"></i>
                                <div class="text-sm font-semibold text-foreground mb-1">Credit Card</div>
                                <div class="text-[11px] font-medium text-muted-foreground">Secure payment</div>
                            </div>
                            <div class="absolute top-4 right-4 h-4 w-4 rounded-full border border-border flex items-center justify-center check-mark">
                                <div class="h-2 w-2 rounded-full bg-[var(--chart-2)] opacity-0 transition-opacity"></div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-card rounded-2xl border border-border p-8 shadow-premium">
                    <h3 class="text-sm font-semibold text-foreground mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-comment-dots text-muted-foreground"></i> Order Notes (Optional)
                    </h3>
                    <textarea name="notes" class="input-base h-24" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-4 bg-primary text-primary-foreground font-semibold rounded-xl hover:opacity-90 transition-opacity shadow-sm active:scale-[0.98]">
                    Place Order
                </button>
            </form>
        </div>

        {{-- Summary --}}
        <div class="lg:col-span-5">
            <div class="sticky top-24 space-y-6">
                <div class="bg-card rounded-2xl border border-border shadow-premium overflow-hidden">
                    <div class="px-8 py-5 border-b border-border bg-muted/50">
                        <h3 class="text-sm font-semibold text-foreground">Order Details</h3>
                    </div>
                    
                    <div class="max-h-[350px] overflow-y-auto custom-scrollbar px-8 py-4 divide-y divide-border">
                        @foreach($cart->items as $item)
                            <div class="py-4 flex items-center gap-4 group">
                                <div class="h-12 w-12 rounded-lg overflow-hidden bg-muted border border-border shrink-0">
                                    <img src="{{ $item->product->image_url }}" class="h-full w-full object-cover">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-semibold text-foreground truncate">{{ $item->product->title }}</div>
                                    <div class="text-[10px] font-medium text-muted-foreground mt-0.5">Qty: {{ $item->quantity }}</div>
                                </div>
                                <div class="text-sm font-semibold text-foreground">${{ number_format($item->subtotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-8 bg-muted/30 border-t border-border">
                        @php
                            $subtotal  = $cart->total;
                            $tax       = round($subtotal * 0.19, 2);
                            $shipping  = $subtotal > 100 ? 0 : 7.99;
                            $total     = $subtotal + $tax + $shipping;
                        @endphp
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-muted-foreground font-medium">Subtotal</span>
                                <span class="font-semibold text-foreground">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-muted-foreground font-medium">Tax (19%)</span>
                                <span class="font-semibold text-foreground">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-muted-foreground font-medium">Shipping</span>
                                <span class="font-semibold {{ $shipping == 0 ? 'text-primary' : 'text-foreground' }}">
                                    {{ $shipping == 0 ? 'Free' : '$'.number_format($shipping, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="h-px bg-border mb-6"></div>

                        <div class="flex justify-between items-end">
                            <span class="text-sm font-semibold text-foreground">Total</span>
                            <span class="text-2xl font-bold text-foreground">
                                ${{ number_format($total, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 rounded-2xl bg-primary/5 border border-primary/10 flex gap-4 items-start">
                    <i class="fa-solid fa-lock text-primary mt-0.5"></i>
                    <div>
                        <div class="text-xs font-semibold text-foreground mb-1">Secure Checkout</div>
                        <p class="text-[11px] text-muted-foreground leading-relaxed">
                            Your payment information is processed securely. We do not store credit card details nor have access to your credit card information.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-option:has(input:checked) .check-mark { @apply border-primary; }
    .payment-option:has(input:checked) .check-mark div { @apply opacity-100; }
    .payment-option:has(input:checked) > div { @apply border-primary bg-primary/5; }
    
    #cod-label:has(input:checked) .check-mark { @apply border-primary; }
    #cod-label:has(input:checked) .check-mark div { @apply bg-primary; }
    #cod-label:has(input:checked) > div { @apply border-primary bg-primary/5; }
    
    #card-label:has(input:checked) .check-mark { @apply border-[var(--chart-2)]; }
    #card-label:has(input:checked) .check-mark div { @apply bg-[var(--chart-2)]; }
    #card-label:has(input:checked) > div { @apply border-[var(--chart-2)] bg-[var(--chart-2)]/5; }
</style>
@endsection

@push('scripts')
<script>
function updatePaymentUI() {}
</script>
@endpush
