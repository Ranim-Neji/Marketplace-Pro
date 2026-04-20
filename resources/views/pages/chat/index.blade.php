@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-layout py-16">
    <div class="flex items-baseline gap-6 mb-12 border-b border-slate-100 dark:border-slate-800 pb-10">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Signal Frequency</h1>
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Communication Hub</div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        {{-- Conversations List --}}
        <div class="lg:col-span-8 space-y-4">
            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <i class="fa-solid fa-comments text-primary"></i> Active Connections
            </h3>

            @forelse($conversations as $conversation)
                @php
                    $other = $conversation->getOtherUser(auth()->id());
                    $lastMessage = $conversation->lastMessage;
                @endphp
                <a href="{{ route('chat.show', $conversation) }}" class="block group">
                    <div class="bg-white dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-slate-900 p-6 shadow-sm hover:shadow-premium hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-5">
                            <div class="relative">
                                <img src="{{ $other->avatar_url }}" class="h-14 w-14 rounded-2xl object-cover border border-slate-100 dark:border-slate-800 shadow-sm">
                                <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-emerald-500 border-2 border-white dark:border-slate-950 rounded-full shadow-sm"></div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">{{ $other->name }}</h4>
                                    @if($conversation->last_message_at)
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $conversation->last_message_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate italic">
                                    {{ $lastMessage ? $lastMessage->body : 'Protocol initialized. Waiting for transmission...' }}
                                </p>
                            </div>

                            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-400 group-hover:text-primary group-hover:bg-primary/5 transition-all">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="py-20 flex flex-col items-center justify-center bg-white dark:bg-slate-950 rounded-[3rem] border border-slate-100 dark:border-slate-900 shadow-sm">
                    <div class="h-16 w-16 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center mb-6 text-slate-200">
                        <i class="fa-solid fa-ghost text-2xl"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">No active frequency detected</p>
                </div>
            @endforelse
        </div>

        {{-- Start New Connection --}}
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-slate-900 p-8 shadow-sm">
                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                    <i class="fa-solid fa-user-plus text-emerald-500"></i> New Protocol
                </h3>

                <div class="space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar pr-2">
                    @forelse($users as $user)
                        <form method="POST" action="{{ route('chat.start', $user) }}">
                            @csrf
                            <button type="submit" class="w-full text-left group">
                                <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-900 transition-all border border-transparent hover:border-slate-100 dark:hover:border-slate-800">
                                    <img src="{{ $user->avatar_url }}" class="h-10 w-10 rounded-xl object-cover">
                                    <div class="min-w-0 flex-1">
                                        <div class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-tighter truncate italic">{{ $user->name }}</div>
                                        <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest truncate">{{ $user->email }}</div>
                                    </div>
                                    <i class="fa-solid fa-plus text-[10px] text-slate-300 group-hover:text-emerald-500 transition-colors"></i>
                                </div>
                            </button>
                        </form>
                    @empty
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center py-10">No users available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
