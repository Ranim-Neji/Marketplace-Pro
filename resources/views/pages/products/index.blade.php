@extends('layouts.app')

@section('title', 'Vault | MarketPlace Pro')

@section('content')
<div class="container-layout py-16">
    <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-12 border-b border-slate-100 dark:border-slate-800 pb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Product Inventory</h1>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">{{ $products->count() }} Products Registered</div>
        </div>
        <a href="{{ route('vendor.products.create') }}" class="btn-primary py-3.5 px-8 text-xs uppercase tracking-[0.2em]">Add New Product</a>
    </div>

    @if($products->isEmpty())
        <div class="py-32 flex flex-col items-center justify-center bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800">
            <div class="h-24 w-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-10">
                <i class="fa-solid fa-box-open text-3xl text-slate-200"></i>
            </div>
            <h2 class="text-xl font-black dark:text-white uppercase tracking-tighter mb-4">Inventory Empty</h2>
            <p class="text-xs text-slate-500 mb-10 uppercase font-bold tracking-widest">No products are currently registered in your inventory</p>
            <a href="{{ route('vendor.products.create') }}" class="btn-primary">Add Your First Product</a>
        </div>
    @else
        <div class="space-y-4">
            {{-- Table Header --}}
            <div class="hidden sm:grid grid-cols-12 gap-6 px-10 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                <div class="col-span-4">Product Details</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2 text-right">Price</div>
                <div class="col-span-2 text-right">Stock</div>
                <div class="col-span-2 text-right">Actions</div>
            </div>

            {{-- Table Body --}}
            @foreach($products as $product)
                <div class="group bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-2xl p-6 sm:px-10 hover:border-indigo-600 transition-all">
                    <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-6">
                        {{-- Asset --}}
                        <div class="col-span-4 flex items-center gap-6">
                            <div class="h-16 w-16 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shrink-0">
                                <img src="{{ $product->image_url }}" class="h-full w-full object-contain p-1">
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter truncate">{{ $product->title }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase">{{ $product->sku ?: 'MODEL-PREMIUM' }}</div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-span-2">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-100 dark:border-slate-800">
                                <div class="h-1.5 w-1.5 rounded-full {{ $product->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>

                        {{-- Price --}}
                        <div class="col-span-2 text-right">
                            <div class="text-sm font-black text-indigo-600 font-mono tracking-tighter">${{ number_format($product->effective_price, 2) }}</div>
                            @if($product->sale_price)
                                <div class="text-[8px] font-bold text-slate-300 line-through uppercase">${{ number_format($product->price, 2) }}</div>
                            @endif
                        </div>

                        {{-- Stock --}}
                        <div class="col-span-2 text-right">
                            <div class="text-sm font-black text-slate-900 dark:text-white">{{ $product->stock }} <span class="text-[9px] text-slate-400 uppercase tracking-tighter ml-1">Units</span></div>
                            <div class="w-16 h-1 bg-slate-100 dark:bg-slate-900 rounded-full mt-2 ml-auto overflow-hidden">
                                <div class="h-full bg-indigo-500" style="width: {{ min(100, ($product->stock / 50) * 100) }}%"></div>
                            </div>
                        </div>

                        {{-- Interaction --}}
                        <div class="col-span-2 flex justify-end gap-3">
                            <a href="{{ route('products.show', $product) }}" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition-all">
                                <i class="fa-solid fa-eye text-[10px]"></i>
                            </a>
                            <a href="{{ route('vendor.products.edit', $product) }}" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-amber-500 hover:border-amber-500 transition-all">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                            </a>
                            <form method="POST" action="{{ route('vendor.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Purge asset?');">
                                @csrf @method('DELETE')
                                <button class="h-9 w-9 flex items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

