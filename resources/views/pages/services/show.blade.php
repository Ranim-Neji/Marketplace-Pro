@extends('layouts.app')
@section('title', $service->name . ' | Services')

@section('content')
<div class="container-layout py-12 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="mb-10 flex items-center gap-3 text-xs font-medium text-muted-foreground">
        <a href="{{ route('home') }}" class="hover:text-foreground transition-colors">Home</a>
        <i class="fa-solid fa-chevron-right text-[8px] opacity-50"></i>
        <a href="{{ route('services.index') }}" class="hover:text-foreground transition-colors">Services</a>
        <i class="fa-solid fa-chevron-right text-[8px] opacity-50"></i>
        @if($service->category)
            <a href="{{ route('services.index', ['category_id' => $service->category_id]) }}" class="hover:text-foreground transition-colors">{{ $service->category->name }}</a>
            <i class="fa-solid fa-chevron-right text-[8px] opacity-50"></i>
        @endif
        <span class="text-foreground truncate max-w-[200px]">{{ $service->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
        {{-- Visuals --}}
        <div class="lg:col-span-6">
            <div class="sticky top-24 space-y-6">
                <div class="relative aspect-square rounded-[2rem] overflow-hidden bg-muted border border-border shadow-premium group">
                    <img src="{{ $service->image_url }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="{{ $service->name }}">
                    
                    @if(!$service->availability)
                        <div class="absolute inset-0 bg-background/60 backdrop-blur-sm flex items-center justify-center">
                            <span class="px-8 py-3 bg-slate-900 text-white text-xs font-black uppercase tracking-[0.3em] rounded-full">Currently Unavailable</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="lg:col-span-6">
            <div class="space-y-10">
                {{-- Header --}}
                <div>
                    @if($service->category)
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <a href="{{ route('services.index', ['category_id' => $service->category_id]) }}"
                               class="px-3 py-1 rounded-full bg-muted border border-border text-[10px] font-semibold uppercase tracking-wider text-muted-foreground hover:text-primary hover:border-primary transition-all">
                                {{ $service->category->name }}
                            </a>
                        </div>
                    @endif

                    <h1 class="text-3xl md:text-5xl font-semibold text-foreground tracking-tight mb-4 font-serif italic">
                        {{ $service->name }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 text-[11px] font-semibold uppercase tracking-wider text-muted-foreground font-mono">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-primary"></i>
                            <span class="text-foreground">Verified Expert</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock opacity-50"></i>
                            <span>Fast Delivery</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved opacity-50"></i>
                            <span>Secure Payment</span>
                        </div>
                    </div>
                </div>

                {{-- Pricing & Actions --}}
                <div class="bg-card rounded-2xl border border-border p-8 shadow-premium relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6">
                        @if($service->availability)
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[var(--chart-3)]/10 text-[var(--chart-3)] text-[10px] font-bold uppercase tracking-wider border border-[var(--chart-3)]/20">
                                <div class="h-1.5 w-1.5 rounded-full bg-[var(--chart-3)] animate-pulse"></div>
                                Accepting Orders
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warning/10 text-warning text-[10px] font-bold uppercase tracking-wider border border-warning/20">
                                <div class="h-1.5 w-1.5 rounded-full bg-warning"></div>
                                Waitlist Only
                            </span>
                        @endif
                    </div>

                    <div class="flex items-end gap-2 mb-8">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Starting from</span>
                            <div class="text-4xl font-bold text-foreground">
                                ${{ number_format($service->price, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @auth
                            @if($service->user_id !== auth()->id())
                                <form method="POST" action="{{ route('chat.start', $service->user) }}">
                                    @csrf
                                    <button type="submit" class="w-full btn-primary py-4 px-8 text-sm font-black uppercase tracking-[0.2em] group flex items-center justify-center gap-3 shadow-xl shadow-primary/20">
                                        <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                                        Inquire About Service
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('vendor.services.edit', $service) }}" class="w-full btn-secondary py-4 px-8 text-sm font-black uppercase tracking-[0.2em] block text-center">
                                    Edit Service Asset
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block text-center btn-primary py-4 text-sm font-black uppercase tracking-[0.2em]">
                                Login to Book Service
                            </a>
                        @endauth
                        
                        <p class="text-[10px] text-center text-muted-foreground font-bold uppercase tracking-widest">Typical response time: < 2 hours</p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest text-muted-foreground font-mono">
                        <div class="h-px flex-1 bg-border"></div>
                        Service Overview
                        <div class="h-px flex-1 bg-border"></div>
                    </div>

                    <div class="text-sm text-foreground leading-relaxed">
                        {!! nl2br(e($service->description)) !!}
                    </div>

                    {{-- Provider Info --}}
                    <div class="p-8 rounded-[2rem] bg-muted/50 border border-border flex items-center gap-6 mt-12 group">
                        <div class="relative">
                            <img src="{{ $service->user->avatar_url }}" class="h-16 w-16 rounded-full border border-border object-cover">
                            <div class="absolute -bottom-1 -right-1 h-5 w-5 bg-primary rounded-full border-2 border-white flex items-center justify-center">
                                <i class="fa-solid fa-check text-[8px] text-white"></i>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-1">Service Provider</div>
                            <div class="text-lg font-bold text-foreground truncate group-hover:text-primary transition-colors">{{ $service->user->name }}</div>
                            <div class="text-[11px] text-muted-foreground mt-0.5">Specializing in {{ $service->category->name ?? 'Premium Services' }}</div>
                        </div>
                        <a href="{{ route('vendors.show', $service->user) }}" class="h-12 w-12 flex items-center justify-center rounded-xl bg-card border border-border text-muted-foreground hover:text-primary hover:border-primary transition-all shadow-sm">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related --}}
    @if($relatedServices->count() > 0)
        <div class="mt-24 pt-12 border-t border-border">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <h4 class="text-2xl font-semibold text-foreground tracking-tight font-serif italic">Related Services</h4>
                    <p class="text-xs text-muted-foreground mt-1 uppercase tracking-widest font-bold">You might also be interested in</p>
                </div>
                <a href="{{ route('services.index', ['category_id' => $service->category_id]) }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:underline">View All</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($relatedServices as $related)
                    <x-service-card :service="$related" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
