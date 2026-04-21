@extends('layouts.admin')
@section('title', 'Active Inventory')

@section('content')
<div class="space-y-10">
    {{-- Search & Controls --}}
    <div class="bg-card/90 backdrop-blur-md p-8 rounded-[2.5rem] border border-border shadow-premium flex flex-col md:flex-row gap-6 items-center justify-between">
        <form method="GET" class="flex flex-1 gap-4 w-full md:max-w-2xl">
            <div class="relative flex-1 group">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-muted-foreground group-focus-within:text-primary transition-colors">
                    <i class="fa-solid fa-barcode text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full bg-accent/30 border-none rounded-2xl py-4 pl-12 pr-6 text-[10px] font-black uppercase tracking-widest placeholder:text-muted-foreground/50 focus:ring-2 focus:ring-primary/20 transition-all italic text-foreground" 
                       placeholder="Scan product ID, title or SKU...">
            </div>
            <select name="status" class="bg-accent/30 border-none rounded-2xl px-6 text-[10px] font-black uppercase tracking-widest text-muted-foreground focus:ring-2 focus:ring-primary/20 transition-all italic">
                <option value="">All Status</option>
                @foreach(['active', 'inactive', 'draft'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="bg-primary text-white px-8 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-premium">Filter</button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.products.index') }}" class="px-6 py-4 rounded-2xl bg-accent text-muted-foreground text-[10px] font-black uppercase tracking-widest hover:text-rose-500 transition-all italic">Clear</a>
            @endif
        </form>
        <a href="{{ route('admin.products.create') }}" class="w-full md:w-auto px-10 py-4 bg-slate-900 dark:bg-white text-white dark:text-black rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-opacity italic shadow-premium text-center">
            New Product
        </a>
    </div>

    {{-- Asset Registry --}}
    <div class="bg-card/90 backdrop-blur-md rounded-[3.5rem] border border-border overflow-hidden shadow-premium">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-accent/30">
                        <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Asset / Node</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Provider</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-center">Status</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Valuation</th>
                        <th class="px-8 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">Inventory</th>
                        <th class="px-10 py-6 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-right">Directives</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($products as $product)
                        <tr class="hover:bg-accent/30 transition-all group">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <img src="{{ $product->image_url }}" class="h-12 w-12 rounded-xl border border-border object-cover shadow-sm">
                                        @if($product->is_featured)
                                            <div class="absolute -top-2 -right-2 h-5 w-5 bg-amber-400 rounded-full flex items-center justify-center text-[8px] text-white border-2 border-card shadow-sm">
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-[11px] font-black text-foreground uppercase tracking-tighter italic">{{ $product->title }}</div>
                                        <div class="text-[8px] font-bold text-muted-foreground uppercase tracking-widest mt-0.5">SKU: {{ $product->sku ?: 'UNASSIGNED' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic hover:text-primary transition-colors cursor-default">{{ $product->user->name }}</div>
                            </td>
                            <td class="px-8 py-8 text-center">
                                @php
                                    $statusColors = ['active' => 'emerald', 'inactive' => 'rose', 'draft' => 'slate'];
                                    $color = $statusColors[$product->status] ?? 'slate';
                                @endphp
                                <span class="px-3 py-1 rounded-lg bg-{{ $color }}-50/50 text-{{ $color }}-600 border border-{{ $color }}-100 text-[8px] font-black uppercase tracking-widest italic">
                                    {{ $product->status }}
                                </span>
                            </td>
                            <td class="px-8 py-8">
                                <div class="text-xs font-black text-foreground font-mono italic">${{ number_format($product->price, 2) }}</div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="text-xs font-black {{ $product->stock < 10 ? 'text-rose-500' : 'text-muted-foreground' }} font-mono italic">{{ $product->stock }} units</div>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="flex h-9 px-4 items-center gap-2 rounded-xl {{ $product->is_featured ? 'bg-amber-400 text-white shadow-premium border-amber-400' : 'bg-card text-muted-foreground border-border' }} border transition-all group/btn">
                                            <i class="fa-solid fa-star text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                            <span class="text-[9px] font-black uppercase tracking-widest italic">{{ $product->is_featured ? 'Featured' : 'Feature' }}</span>
                                        </button>
                                    </form>

                                    <a href="{{ route('products.show', $product) }}" target="_blank" class="flex h-9 px-4 items-center gap-2 rounded-xl bg-card border border-border text-muted-foreground hover:text-primary transition-all shadow-premium group/btn">
                                        <i class="fa-solid fa-eye text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest italic">View</span>
                                    </a>

                                    <a href="{{ route('admin.products.edit', $product) }}" class="flex h-9 px-4 items-center gap-2 rounded-xl bg-card border border-border text-muted-foreground hover:text-primary transition-all shadow-premium group/btn">
                                        <i class="fa-solid fa-pen-to-square text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest italic">Edit</span>
                                    </a>

                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Purge asset?');">
                                        @csrf @method('DELETE')
                                        <button class="flex h-9 px-4 items-center gap-2 rounded-xl bg-rose-50 text-rose-500 border border-rose-100 hover:bg-rose-500 hover:text-white transition-all shadow-premium group/btn">
                                            <i class="fa-solid fa-trash-can text-[10px] transition-transform group-hover/btn:scale-110"></i>
                                            <span class="text-[9px] font-black uppercase tracking-widest italic">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-10 py-8 bg-accent/30 border-t border-border">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
