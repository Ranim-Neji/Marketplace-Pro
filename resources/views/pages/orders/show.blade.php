@extends('layouts.app')

@section('title', 'Receipt #' . $order->order_number . ' | MarketPlace Pro')

@section('content')
<div class="container-layout py-16">
    {{-- Protocol Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-16 border-b border-slate-100 dark:border-slate-800 pb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('orders.index') }}" class="h-10 w-10 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
            <div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Acquisition Confirmed</div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Order #{{ $order->order_number }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-4">
             <span class="px-5 py-2 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-800">
                Protocol: {{ ucfirst($order->status) }}
            </span>
            <button class="btn-primary py-2.5 px-6 text-[9px] uppercase tracking-[0.2em]">Export Invoice</button>
        </div>
    </div>

    {{-- Strategy Blocks --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl p-8">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Liquidation Strategy</div>
            <div class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ str_replace('_', ' ', $order->payment_method) }}</div>
            <div class="text-[9px] font-bold text-primary uppercase tracking-tighter mt-1">{{ ucfirst($order->payment_status) }} verified</div>
        </div>

        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl p-8">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Logistics Command</div>
            <div class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">Verified Courier Service</div>
            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-1">Status: Pre-allocation</div>
        </div>

        <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl p-8">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Temporal Index</div>
            <div class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $order->created_at->format('d M Y') }}</div>
            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-1">{{ $order->created_at->format('H:i') }} Zulu</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        {{-- Itemized Breakdown --}}
        <div class="lg:col-span-8 space-y-12">
            <div>
                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] mb-8 border-b border-slate-100 dark:border-slate-800 pb-4">Asset Breakdown</h3>
                <div class="space-y-1">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-6 p-6 hover:bg-white dark:hover:bg-slate-900 border-b border-slate-50 dark:border-slate-900 transition-all group">
                            <div class="h-16 w-16 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 shrink-0">
                                <img src="{{ $item->product->image_url ?? '#' }}" class="h-full w-full object-contain p-1">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tighter truncate">{{ $item->product_title }}</div>
                                <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase">Unit Liquidity: ${{ number_format($item->price, 2) }}</div>
                            </div>
                            <div class="text-center px-6">
                                <div class="text-[10px] font-black text-slate-900 dark:text-white">x{{ $item->quantity }}</div>
                            </div>
                            <div class="text-right min-w-[100px]">
                                <div class="text-xs font-black text-primary font-mono">${{ number_format($item->subtotal, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] mb-8 border-b border-slate-100 dark:border-slate-800 pb-4">Logistics Target</h3>
                <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl p-8">
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-400 leading-relaxed uppercase tracking-widest italic">
                        {{ $order->shipping_address }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Liquidation Finality --}}
        <div class="lg:col-span-4 sticky top-28">
            <div class="bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-10 shadow-sm space-y-10">
                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-900 pb-4">Finality Statement</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase">
                        <span>Base Liquidity</span>
                        <span class="text-slate-900 dark:text-white font-mono">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase">
                        <span>Terminal Retention</span>
                        <span class="text-slate-900 dark:text-white font-mono">${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase">
                        <span>Logistics Load</span>
                        @if($order->shipping == 0)
                            <span class="text-primary font-black">WAIVED</span>
                        @else
                            <span class="text-slate-900 dark:text-white font-mono">${{ number_format($order->shipping, 2) }}</span>
                        @endif
                    </div>
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-900">
                        <div class="flex justify-between items-end">
                            <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">Aggregate</span>
                            <div class="text-3xl font-black text-primary font-mono tracking-tighter">${{ number_format($order->total, 2) }}</div>
                        </div>
                    </div>
                </div>

                @if($order->notes)
                    <div class="pt-8 border-t border-slate-100 dark:border-slate-900">
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Protocol Directives</div>
                        <p class="text-[10px] font-medium text-slate-500 italic leading-relaxed">"{{ $order->notes }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

