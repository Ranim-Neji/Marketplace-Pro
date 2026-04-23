@extends('layouts.admin')
@section('title', 'Logistics Manifest Analysis')

@section('content')
<div class="space-y-10">
    {{-- Manifest Header --}}
    <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium flex flex-col md:flex-row justify-between items-center gap-8">
        <div>
            <div class="text-[9px] font-black text-primary uppercase tracking-[0.3em] mb-2 italic">Protocol: ORDER_MANIFEST</div>
            <h1 class="text-3xl font-black text-foreground uppercase tracking-tighter italic font-serif">Manifest #{{ $order->order_number }}</h1>
            <div class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest mt-2 italic">Established: {{ $order->created_at->format('M d, Y • H:i:s') }}</div>
        </div>
        <div class="flex gap-4">
            <button onclick="window.print()" class="px-8 py-4 bg-dark text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all italic flex items-center gap-2">
                <i class="fa-solid fa-print"></i>
                Print Manifest
            </button>
            <a href="{{ route('admin.orders.index') }}" class="px-8 py-4 bg-accent text-muted-foreground rounded-2xl text-[10px] font-black uppercase tracking-widest hover:text-primary transition-all italic border border-border">Exit Module</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        {{-- Cargo Details --}}
        <div class="lg:col-span-8 space-y-10">
            <div class="bg-card/90 backdrop-blur-md rounded-[3.5rem] border border-border overflow-hidden shadow-premium">
                <div class="p-10 border-b border-border flex justify-between items-center">
                    <h3 class="text-xs font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Manifest Cargo Assets</h3>
                    <span class="px-3 py-1 rounded-lg bg-accent text-muted-foreground text-[8px] font-black uppercase tracking-widest italic border border-border">
                        {{ count($order->items) }} Unique Segments
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-accent/30">
                                <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Asset Description</th>
                                <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Unit Value</th>
                                <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-center">Quantity</th>
                                <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-right">Aggregate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-accent/30 transition-all group text-[10px] font-bold text-foreground italic">
                                    <td class="px-10 py-8 uppercase tracking-tight">{{ $item->product_title }}</td>
                                    <td class="px-8 py-8 font-mono text-muted-foreground">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-8 py-8 text-center font-mono text-muted-foreground">{{ $item->quantity }}</td>
                                    <td class="px-10 py-8 text-right font-mono text-foreground">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium space-y-6">
                <h3 class="text-xs font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Delivery Coordinates</h3>
                <div class="p-8 rounded-3xl bg-accent/50 text-[11px] font-bold text-foreground italic leading-relaxed border border-border shadow-inner">
                    {{ $order->shipping_address }}
                </div>
            </div>
        </div>

        {{-- Configuration Node --}}
        <div class="lg:col-span-4 space-y-10">
            <div class="bg-card/90 backdrop-blur-md p-10 rounded-[3rem] border border-border shadow-premium space-y-8 sticky top-32">
                <h3 class="text-xs font-black text-foreground uppercase tracking-[0.2em] italic font-serif">Protocol Directives</h3>
                
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-6">
                    @csrf @method('PATCH')

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Operational Phase</label>
                        <select name="status" class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                            @foreach(['pending','processing','validated','shipped','delivered','cancelled'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Credit Liquidity Status</label>
                        <select name="payment_status" class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                            @foreach(['pending','paid','failed','refunded'] as $paymentStatus)
                                <option value="{{ $paymentStatus }}" @selected(old('payment_status', $order->payment_status) === $paymentStatus)>{{ ucfirst($paymentStatus) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-muted-foreground uppercase tracking-widest px-4 italic">Administrator Log</label>
                        <textarea name="notes" rows="4" 
                                  class="w-full bg-accent/30 border-none rounded-2xl py-4 px-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                                  placeholder="Log entry for this manifest...">{{ old('notes', $order->notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-5 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-premium">
                        Update Manifest
                    </button>
                </form>

                <div class="pt-8 border-t border-border space-y-4">
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest italic">
                        <span class="text-muted-foreground italic">Personnel</span>
                        <span class="text-foreground italic">{{ $order->user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest italic">
                        <span class="text-muted-foreground italic">Total Valuation</span>
                        <span class="text-primary font-mono italic text-sm font-black">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
