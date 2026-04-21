@extends('layouts.app')

@section('title', 'Calibrate Asset | MarketPlace Pro')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
        <div>
            <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Asset Revision</div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic underline decoration-indigo-500 decoration-4 underline-offset-8">Calibrate</h1>
        </div>
        <div class="flex items-center gap-6">
            <a href="{{ route('products.show', $product) }}" class="text-xs font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-eye"></i> View Live
            </a>
            <a href="{{ route('vendor.products.index') }}" class="text-xs font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Return to Vault
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('vendor.products.update', $product) }}" enctype="multipart/form-data" class="space-y-12">
        @csrf @method('PUT')
        
        <div class="card-premium !p-10">
            @include('pages.products.partials.form')
        </div>

        @if($product->images->isNotEmpty())
            <div class="space-y-6">
                <h3 class="text-xs font-black text-indigo-500 uppercase tracking-widest flex items-center gap-2 px-1">
                    <i class="fa-solid fa-images"></i> Active Gallery Nodes
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($product->images as $image)
                        <div class="group relative aspect-square rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:border-indigo-500 transition-all">
                            <img src="{{ $image->image_url }}" alt="Gallery image" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <form action="{{ route('vendor.products.delete-image', [$product, $image]) }}" method="POST" onsubmit="return confirm('Delete image node?');">
                                    @csrf @method('DELETE')
                                    <button class="h-10 w-10 bg-rose-500 text-white rounded-xl shadow-xl hover:scale-110 active:scale-95 transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex justify-end gap-6 pt-12 border-t border-slate-100 dark:border-slate-800">
            <button type="button" onclick="history.back()" class="px-8 py-4 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 dark:hover:text-white transition-colors">
                Cancel Revision
            </button>
            <button type="submit" class="btn-primary shadow-2xl shadow-indigo-500/20">
                Update Asset Logic
            </button>
        </div>
    </form>
</div>
@endsection

