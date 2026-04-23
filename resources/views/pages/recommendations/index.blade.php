@extends('layouts.app')
@section('title', 'Intelligence Feed | MarketPlace Pro')

@section('content')
<div class="container-layout py-16">
    <div class="mb-16">
        <div class="flex items-center gap-4 text-[11px] font-black text-primary uppercase tracking-[0.4em] mb-4">
            <div class="h-px w-12 bg-primary"></div>
            Intelligence Engine
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Correlated Protocol Feed</h1>
        <p class="text-xs text-slate-500 mt-6 uppercase font-bold tracking-[0.2em] italic">Assets curated based on your interaction behavioral matrix.</p>
    </div>

    @if($recommendedProducts->isEmpty())
        <div class="py-32 flex flex-col items-center justify-center bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800">
            <i class="fa-solid fa-brain-circuit text-4xl text-slate-200 mb-8"></i>
            <h2 class="text-xl font-black dark:text-white uppercase tracking-tighter mb-4 italic">Engine Calibrating</h2>
            <p class="text-xs text-slate-500 uppercase font-bold tracking-widest">Interact with more assets to generate a behavioral profile.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            @foreach($recommendedProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    @endif

    @if($trendingProducts->isNotEmpty())
        <div class="mt-32 pt-16 border-t border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Global Hotnodes</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Maximum interaction density in the last 24h</p>
                </div>
                <a href="{{ route('catalog.index', ['sort' => 'popular']) }}" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline italic">View All Popular</a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @foreach($trendingProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
section
