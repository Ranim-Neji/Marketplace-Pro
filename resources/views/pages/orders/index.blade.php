@extends('layouts.app')

@section('title', 'Logs | MarketPlace Pro')

@section('content')
<div class="container-layout py-16">
    <div class="flex items-baseline gap-6 mb-12 border-b border-border dark:border-border pb-6">
        <h1 class="text-3xl font-black text-foreground dark:text-white uppercase tracking-tighter">Transaction Protocol</h1>
        <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">{{ $orders->count() }} Signal Logs</div>
    </div>

    @if($orders->isEmpty())
        <div class="py-32 flex flex-col items-center justify-center bg-white dark:bg-dark rounded-[3rem] border border-border dark:border-border">
            <div class="h-24 w-24 bg-muted dark:bg-dark rounded-full flex items-center justify-center mb-10">
                <i class="fa-solid fa-scroll text-3xl text-border"></i>
            </div>
            <h2 class="text-xl font-black dark:text-white uppercase tracking-tighter mb-4">No data logged</h2>
            <p class="text-xs text-muted-foreground mb-10 uppercase font-bold tracking-widest">Your acquisition history is currently unpopulated</p>
            <a href="{{ route('catalog.index') }}" class="btn-primary">Browse Assets</a>
        </div>
    @else
        <div class="space-y-4">
            {{-- Protocol Table Header --}}
            <div class="hidden sm:grid grid-cols-12 gap-6 px-10 text-[10px] font-black text-muted-foreground uppercase tracking-widest mb-2">
                <div class="col-span-3">Identification</div>
                <div class="col-span-2">Protocol Status</div>
                <div class="col-span-2">Execution Date</div>
                <div class="col-span-3 text-right">Liquidation Value</div>
                <div class="col-span-2 text-right">Interaction</div>
            </div>

            {{-- Table Body --}}
            @foreach($orders as $order)
                <div class="group bg-white dark:bg-dark border border-border dark:border-border rounded-2xl p-6 sm:px-10 hover:border-primary transition-all">
                    <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-6">
                        {{-- ID --}}
                        <div class="col-span-3 flex items-center gap-4">
                            <div class="h-10 w-10 bg-muted dark:bg-dark rounded-xl flex items-center justify-center text-[10px] font-black text-primary border border-border dark:border-border">
                                #
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-black text-foreground dark:text-white uppercase tracking-tighter truncate">{{ $order->order_number }}</div>
                                <div class="text-[8px] font-bold text-muted-foreground uppercase">Transaction ID</div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-span-2">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-muted dark:bg-dark text-foreground dark:text-white border border-border dark:border-border">
                                @if($order->status == 'completed')
                                    <div class="h-1.5 w-1.5 bg-primary rounded-full"></div>
                                @elseif($order->status == 'cancelled')
                                    <div class="h-1.5 w-1.5 bg-warning rounded-full"></div>
                                @else
                                    <div class="h-1.5 w-1.5 bg-primary rounded-full"></div>
                                @endif
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        {{-- Date --}}
                        <div class="col-span-2">
                            <div class="text-xs font-black text-foreground dark:text-white uppercase tracking-tighter">{{ $order->created_at->format('d M y') }}</div>
                            <div class="text-[9px] font-bold text-muted-foreground uppercase">{{ $order->created_at->format('H:i') }} Zulu</div>
                        </div>

                        {{-- Value --}}
                        <div class="col-span-3 text-right">
                            <div class="text-sm font-black text-primary font-mono tracking-tighter">${{ number_format($order->total, 2) }}</div>
                            <div class="text-[9px] font-black text-muted-foreground uppercase tracking-widest">{{ ucfirst($order->payment_status) }}</div>
                        </div>

                        {{-- Interaction --}}
                        <div class="col-span-2 flex justify-end gap-3">
                            <a href="{{ route('orders.show', $order) }}" class="h-9 w-9 flex items-center justify-center rounded-lg bg-muted dark:bg-dark border border-border dark:border-border text-muted-foreground hover:text-primary hover:border-primary transition-all">
                                <i class="fa-solid fa-expand text-[10px]"></i>
                            </a>
                            @if(in_array($order->status, ['pending', 'processing']))
                                <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Abort transaction?');">
                                    @csrf @method('PATCH')
                                    <button class="h-9 w-9 flex items-center justify-center rounded-lg bg-rose-50 dark:bg-warning/10 border border-warning dark:border-warning text-warning hover:bg-warning hover:text-white transition-all">
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

