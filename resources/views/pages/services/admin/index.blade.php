@extends('layouts.app')

@section('title', 'Admin | All Services')

@section('content')
<div class="container-layout py-16">
    <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-12 border-b border-slate-100 dark:border-slate-800 pb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Global Service Registry</h1>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Administrative Control Panel | {{ $services->total() }} total services</div>
        </div>
    </div>

    <div class="space-y-4">
        {{-- Table Header --}}
        <div class="hidden sm:grid grid-cols-12 gap-6 px-10 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
            <div class="col-span-4">Service Details</div>
            <div class="col-span-3">Provider</div>
            <div class="col-span-2">Status</div>
            <div class="col-span-1 text-right">Price</div>
            <div class="col-span-2 text-right">Actions</div>
        </div>

        {{-- Table Body --}}
        @foreach($services as $service)
            <div class="group bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-2xl p-6 sm:px-10 hover:border-primary transition-all">
                <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-6">
                    {{-- Asset --}}
                    <div class="col-span-4 flex items-center gap-6">
                        <div class="h-16 w-16 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shrink-0">
                            <img src="{{ $service->image_url }}" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter truncate">{{ $service->name }}</div>
                            <div class="text-[8px] font-bold text-slate-400 uppercase">{{ $service->category ? $service->category->name : 'No Category' }}</div>
                        </div>
                    </div>

                    {{-- Provider --}}
                    <div class="col-span-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $service->user->avatar_url }}" class="h-8 w-8 rounded-full border border-border">
                            <div class="min-w-0">
                                <div class="text-[10px] font-black text-slate-900 dark:text-white uppercase truncate">{{ $service->user->name }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase truncate">{{ $service->user->email }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-span-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-100 dark:border-slate-800">
                            <div class="h-1.5 w-1.5 rounded-full {{ $service->availability ? 'bg-primary' : 'bg-slate-300' }}"></div>
                            {{ $service->availability ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    {{-- Price --}}
                    <div class="col-span-1 text-right">
                        <div class="text-sm font-black text-primary font-mono tracking-tighter">${{ number_format($service->price, 2) }}</div>
                    </div>

                    {{-- Interaction --}}
                    <div class="col-span-2 flex justify-end gap-3">
                        <a href="{{ route('services.show', $service) }}" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-primary transition-all">
                            <i class="fa-solid fa-eye text-[10px]"></i>
                        </a>
                        <a href="{{ route('admin.services.edit', $service) }}" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-accent transition-all">
                            <i class="fa-solid fa-pen text-[10px]"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline" onsubmit="return confirm('ADMIN: Force delete service?');">
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
</div>
@endsection
