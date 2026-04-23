@extends('layouts.admin')
@section('title', 'Global Logistics')

@section('content')
<div class="space-y-10">
    {{-- Filtering & Controls --}}
    <div class="bg-card/90 backdrop-blur-md p-8 rounded-[2.5rem] border border-border shadow-premium flex flex-col md:flex-row gap-6 items-center justify-between">
        <form method="GET" class="flex flex-1 gap-4 w-full md:max-w-2xl">
            <div class="relative flex-1 group">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-muted-foreground group-focus-within:text-primary transition-colors">
                    <i class="fa-solid fa-receipt text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full bg-accent/30 border-none rounded-2xl py-4 pl-12 pr-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                       placeholder="Locate order manifest by number...">
            </div>
            <select name="status" class="bg-accent/30 border-none rounded-2xl px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                <option value="">All Statuses</option>
                @foreach(['pending','processing','validated','shipped','delivered','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="bg-primary text-white px-8 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-premium">Filter</button>
        </form>
    </div>

    {{-- Order Registry --}}
    <div class="bg-card/90 backdrop-blur-md rounded-[3.5rem] border border-border overflow-hidden shadow-premium">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-accent/30">
                        <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Protocol ID</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Personnel</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Phase</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Valuation</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-center">Timestamp</th>
                        <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-right">Directive</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($orders as $order)
                        <tr class="hover:bg-accent/30 transition-all group">
                            <td class="px-10 py-8">
                                <div class="text-[10px] font-black text-primary font-mono italic">#{{ $order->order_number }}</div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $order->user->avatar_url }}" class="h-8 w-8 rounded-lg border border-border object-cover">
                                    <div class="text-[10px] font-black text-foreground uppercase tracking-tighter italic">{{ $order->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                @php
                                    $colors = [
                                        'pending' => 'accent', 'processing' => 'dark', 'validated' => 'primary',
                                        'shipped' => 'primary', 'delivered' => 'primary', 'cancelled' => 'warning', 'completed' => 'dark'
                                    ];
                                    $color = $colors[$order->status] ?? 'indigo';
                                @endphp
                                <span class="px-3 py-1 rounded-lg bg-{{ $color }}/10 text-{{ $color }} border border-{{ $color }}/20 text-[8px] font-black uppercase tracking-widest italic">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-8 py-8">
                                <div class="text-xs font-black text-foreground font-mono italic">${{ number_format($order->total, 2) }}</div>
                                <div class="text-[8px] text-muted-foreground uppercase font-mono italic">{{ $order->items_count ?? $order->items->count() }} assets</div>
                            </td>
                            <td class="px-8 py-8 text-center text-[9px] font-bold text-muted-foreground uppercase tracking-widest italic">
                                {{ $order->created_at->format('M d, H:i') }}
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="flex h-9 px-4 items-center gap-2 rounded-xl bg-card border border-border text-muted-foreground hover:text-primary transition-all shadow-premium group/btn">
                                        <i class="fa-solid fa-magnifying-glass-chart text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest italic">Analyze</span>
                                    </a>
                                    <a href="{{ route('admin.orders.show', $order) }}" target="_blank" class="flex h-9 px-4 items-center gap-2 rounded-xl bg-card border border-border text-muted-foreground hover:text-primary transition-all shadow-premium group/btn">
                                        <i class="fa-solid fa-file-invoice-dollar text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest italic">Invoice</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-10 py-8 bg-accent/30 border-t border-border">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
