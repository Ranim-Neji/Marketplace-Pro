@extends('layouts.app')
@section('title', 'Vendor Dashboard')

@section('content')
<div class="container-layout py-12 lg:py-16">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-semibold text-foreground tracking-tight font-serif italic">Vendor Dashboard</h1>
            <p class="text-sm text-muted-foreground mt-2">Manage your shop, products, and track your performance.</p>
        </div>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('vendor.products.create') }}">
                <x-button size="lg" class="px-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    New Product
                </x-button>
            </a>
            <a href="{{ route('vendor.services.create') }}">
                <x-button size="lg" variant="outline" class="px-8 border-primary text-primary hover:bg-primary hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    New Service
                </x-button>
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        {{-- Products --}}
        <div class="bg-card rounded-2xl border border-border p-8 shadow-sm group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-6">
                <div class="h-12 w-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-500 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-box-open text-xl"></i>
                </div>
            </div>
            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1 font-mono italic">Assets</div>
            <div class="text-3xl font-bold text-foreground font-serif italic">{{ $totalProducts }}</div>
        </div>

        {{-- Services --}}
        <div class="bg-card rounded-2xl border border-border p-8 shadow-sm group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-6">
                <div class="h-12 w-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-500 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
            </div>
            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1 font-mono italic">Services</div>
            <div class="text-3xl font-bold text-foreground font-serif italic">{{ $totalServices }}</div>
        </div>

        {{-- Sales --}}
        <div class="bg-card rounded-2xl border border-border p-8 shadow-sm group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-6">
                <div class="h-12 w-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-500 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                </div>
            </div>
            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1 font-mono italic">Orders</div>
            <div class="text-3xl font-bold text-foreground font-serif italic">{{ $totalSalesCount }}</div>
        </div>

        {{-- Revenue --}}
        <div class="bg-card rounded-2xl border border-border p-8 shadow-sm group hover:border-primary/30 transition-all">
            <div class="flex justify-between items-start mb-6">
                <div class="h-12 w-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-500 transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-wallet text-xl"></i>
                </div>
            </div>
            <div class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1 font-mono italic">Revenue</div>
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
