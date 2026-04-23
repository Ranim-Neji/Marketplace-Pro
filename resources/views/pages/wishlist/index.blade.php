@extends('layouts.app')
@section('title', 'Wishlist | MarketPlace Pro')

@section('content')
<div class="container-layout py-16">
    <div class="flex items-baseline gap-6 mb-12 border-b border-slate-100 dark:border-slate-800 pb-10">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Wishlist</h1>
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $wishlist->total() }} Items Saved</div>
    </div>

    @if($wishlist->isEmpty())
        <div class="py-32 flex flex-col items-center justify-center bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 animate-fade-in">
            <div class="h-24 w-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-10 group">
                <i class="fa-solid fa-heart text-3xl text-slate-200 group-hover:text-warning transition-colors"></i>
            </div>
            <h2 class="text-xl font-black dark:text-white uppercase tracking-tighter mb-4 italic">Wishlist Empty</h2>
            <p class="text-xs text-slate-500 mb-10 uppercase font-bold tracking-widest">No products are currently in your wishlist</p>
            <a href="{{ route('catalog.index') }}" class="btn-primary py-4 px-12 text-[10px] uppercase tracking-[0.3em] font-black italic">
                Explore Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            @foreach($wishlist as $item)
                <x-product-card :product="$item->product" />
            @endforeach
        </div>
        <div class="mt-20">
            {{ $wishlist->links() }}
        </div>
    @endif
</div>
@endsection
