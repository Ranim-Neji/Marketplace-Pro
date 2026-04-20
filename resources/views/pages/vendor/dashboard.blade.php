@extends('layouts.app')
@section('title', 'Vendor Dashboard')

@section('content')
<div class="container-layout py-12 lg:py-16">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-semibold text-foreground tracking-tight font-serif italic">Vendor Dashboard</h1>
            <p class="text-sm text-muted-foreground mt-2">Manage your shop, products, and track your performance.</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('vendor.products.create') }}">
                <x-button size="lg" class="px-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Add New Product
                </x-button>
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-card rounded-2xl border border-border p-8 shadow-sm group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-6">
                <div class="h-12 w-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                </div>
            </div>
            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1 font-mono italic">Total Products</div>
            <div class="text-3xl font-bold text-foreground font-serif italic">{{ $totalProducts }}</div>
        </div>

        <div class="bg-card rounded-2xl border border-border p-8 shadow-sm group hover:border-emerald-500/30 transition-all">
            <div class="flex justify-between items-start mb-6">
                <div class="h-12 w-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-600 transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.451 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                </div>
            </div>
            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1 font-mono italic">Total Sales</div>
            <div class="text-3xl font-bold text-foreground font-serif italic">{{ $totalSalesCount }}</div>
        </div>

        <div class="bg-card rounded-2xl border border-border p-8 shadow-sm group hover:border-amber-500/30 transition-all">
            <div class="flex justify-between items-start mb-6">
                <div class="h-12 w-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-600 transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1 font-mono italic">Total Revenue</div>
            <div class="text-3xl font-bold text-foreground font-serif italic">${{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Recent Products --}}
        <div class="bg-card rounded-2xl border border-border overflow-hidden shadow-sm">
            <div class="px-8 py-6 border-b border-border flex justify-between items-center bg-muted/30">
                <h3 class="text-sm font-bold uppercase tracking-widest font-mono italic">Recent Products</h3>
                <a href="{{ route('vendor.products.index') }}" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-widest font-mono">View All</a>
            </div>
            <div class="divide-y divide-border">
                @forelse($recentProducts as $product)
                    <div class="px-8 py-6 flex items-center gap-4 hover:bg-muted/20 transition-colors">
                        <img src="{{ $product->image_url }}" class="h-12 w-12 rounded-lg object-cover border border-border">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold truncate">{{ $product->title }}</h4>
                            <p class="text-xs text-muted-foreground uppercase font-mono mt-1">${{ number_format($product->price, 2) }} • {{ $product->stock }} in stock</p>
                        </div>
                        <a href="{{ route('vendor.products.edit', $product) }}">
                            <x-button variant="outline" size="sm">Edit</x-button>
                        </a>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <p class="text-sm text-muted-foreground italic">No products added yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Sales --}}
        <div class="bg-card rounded-2xl border border-border overflow-hidden shadow-sm">
            <div class="px-8 py-6 border-b border-border flex justify-between items-center bg-muted/30">
                <h3 class="text-sm font-bold uppercase tracking-widest font-mono italic">Recent Sales</h3>
            </div>
            <div class="divide-y divide-border">
                @forelse($recentSales as $sale)
                    <div class="px-8 py-6 flex items-center gap-4 hover:bg-muted/20 transition-colors">
                        <div class="h-10 w-10 rounded-full bg-muted flex items-center justify-center overflow-hidden border border-border">
                            <img src="{{ $sale->order->user->avatar_url }}" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold truncate">{{ $sale->order->user->name }}</h4>
                            <p class="text-xs text-muted-foreground truncate">{{ $sale->product->title }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold">${{ number_format($sale->price * $sale->quantity, 2) }}</div>
                            <div class="text-[10px] text-muted-foreground uppercase font-mono mt-1">{{ $sale->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <p class="text-sm text-muted-foreground italic">No sales yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
