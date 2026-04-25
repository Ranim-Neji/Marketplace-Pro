@extends('layouts.app')

@section('title', 'Service Registry | MarketPlace Pro')

@section('content')
<div class="container-layout py-16">
    <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-12 border-b border-slate-100 dark:border-slate-800 pb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Service Catalog</h1>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">{{ $services->count() }} Services Registered</div>
        </div>
        <a href="{{ route('vendor.services.create') }}" class="btn-primary py-3.5 px-8 text-xs uppercase tracking-[0.2em]">Add New Service</a>
    </div>

    @if($services->isEmpty())
        <div class="py-32 flex flex-col items-center justify-center bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800">
            <div class="h-24 w-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-10">
                <i class="fa-solid fa-handshake text-3xl text-slate-200"></i>
            </div>
            <h2 class="text-xl font-black dark:text-white uppercase tracking-tighter mb-4">No Services Found</h2>
            <p class="text-xs text-slate-500 mb-10 uppercase font-bold tracking-widest">You haven't registered any professional services yet</p>
            <a href="{{ route('vendor.services.create') }}" class="btn-primary">Add Your First Service</a>
        </div>
    @else
        <div class="space-y-4">
            {{-- Table Header --}}
            <div class="hidden sm:grid grid-cols-12 gap-6 px-10 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                <div class="col-span-6">Service Details</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2 text-right">Starting Price</div>
                <div class="col-span-2 text-right">Actions</div>
            </div>

            {{-- Table Body --}}
            @foreach($services as $service)
                <div class="group bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-2xl p-6 sm:px-10 hover:border-primary transition-all">
                    <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-6">
                        {{-- Asset --}}
                        <div class="col-span-6 flex items-center gap-6">
                            <div class="h-16 w-16 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shrink-0">
                                <img src="{{ $service->image_url }}" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter truncate">{{ $service->name }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase">{{ $service->category->name ?? 'Professional Service' }}</div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-span-2">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-100 dark:border-slate-800">
                                <div class="h-1.5 w-1.5 rounded-full {{ $service->availability ? 'bg-primary' : 'bg-slate-300' }}"></div>
                                {{ $service->availability ? 'Available' : 'Busy' }}
                            </span>
                        </div>

                        {{-- Price --}}
                        <div class="col-span-2 text-right">
                            <div class="text-sm font-black text-primary font-mono tracking-tighter">${{ number_format($service->price, 2) }}</div>
                        </div>

                        {{-- Interaction --}}
                        <div class="col-span-2 flex justify-end gap-3">
                            <a href="{{ route('services.show', $service) }}" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-primary transition-all">
                                <i class="fa-solid fa-eye text-[10px]"></i>
                            </a>
                            <a href="{{ route('vendor.services.edit', $service) }}" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-accent transition-all">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                            </a>
                            <form method="POST" action="{{ route('vendor.services.destroy', $service) }}" class="inline" onsubmit="return confirm('Archive service?');">
                                @csrf @method('DELETE')
                                <button class="h-9 w-9 flex items-center justify-center rounded-lg bg-warning/10 border border-warning/20 text-warning hover:bg-warning hover:text-white transition-all">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
