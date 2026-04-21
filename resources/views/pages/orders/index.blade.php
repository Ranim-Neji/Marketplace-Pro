@extends('layouts.app')

@section('title', 'Logs | MarketPlace Pro')

@section('content')
<div class="container-layout py-16">
    <div class="flex items-baseline gap-6 mb-12 border-b border-slate-100 dark:border-slate-800 pb-6">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Transaction Protocol</h1>
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $orders->count() }} Signal Logs</div>
    </div>

    @if($orders->isEmpty())
        <div class="py-32 flex flex-col items-center justify-center bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800">
            <div class="h-24 w-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-10">
                <i class="fa-solid fa-scroll text-3xl text-slate-200"></i>
            </div>
            <h2 class="text-xl font-black dark:text-white uppercase tracking-tighter mb-4">No data logged</h2>
            <p class="text-xs text-slate-500 mb-10 uppercase font-bold tracking-widest">Your acquisition history is currently unpopulated</p>
            <a href="{{ route('catalog.index') }}" class="btn-primary">Browse Assets</a>
        </div>
    @else
        <div class="space-y-4">
            {{-- Protocol Table Header --}}
            <div class="hidden sm:grid grid-cols-12 gap-6 px-10 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                <div class="col-span-3">Identification</div>
                <div class="col-span-2">Protocol Status</div>
                <div class="col-span-2">Execution Date</div>
                <div class="col-span-3 text-right">Liquidation Value</div>
                <div class="col-span-2 text-right">Interaction</div>
            </div>

            {{-- Table Body --}}
            @foreach($orders as $order)
                <div class="group bg-white dark:bg-slate-950 border border-slate-100 dark:border-slate-900 rounded-2xl p-6 sm:px-10 hover:border-indigo-600 transition-all">
                    <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-6">
                        {{-- ID --}}
                        <div class="col-span-3 flex items-center gap-4">
                            <div class="h-10 w-10 bg-slate-50 dark:bg-slate-900 rounded-xl flex items-center justify-center text-[10px] font-black text-indigo-600 border border-slate-100 dark:border-slate-800">
                                #
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter truncate">{{ $order->order_number }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase">Transaction ID</div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-span-2">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-100 dark:border-slate-800">
                                @if($order->status == 'completed')
                                    <div class="h-1.5 w-1.5 bg-emerald-500 rounded-full"></div>
                                @elseif($order->status == 'cancelled')
                                    <div class="h-1.5 w-1.5 bg-rose-500 rounded-full"></div>
                                @else
                                    <div class="h-1.5 w-1.5 bg-indigo-500 rounded-full"></div>
                                @endif
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        {{-- Date --}}
                        <div class="col-span-2">
                            <div class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ $order->created_at->format('d M y') }}</div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase">{{ $order->created_at->format('H:i') }} Zulu</div>
                        </div>

                        {{-- Value --}}
                        <div class="col-span-3 text-right">
                            <div class="text-sm font-black text-indigo-600 font-mono tracking-tighter">${{ number_format($order->total, 2) }}</div>
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ ucfirst($order->payment_status) }}</div>
                        </div>

                        {{-- Interaction --}}
                        <div class="col-span-2 flex justify-end gap-3">
                            <a href="{{ route('orders.show', $order) }}" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition-all">
                                <i class="fa-solid fa-expand text-[10px]"></i>
                            </a>
                            @if(in_array($order->status, ['pending', 'processing']))
                                <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Abort transaction?');">
                                    @csrf @method('PATCH')
                                    <button class="h-9 w-9 flex items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

