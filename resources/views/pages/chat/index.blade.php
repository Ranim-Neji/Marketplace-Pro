@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-layout py-12">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end gap-4 mb-12 border-b border-border pb-10">
        <div>
            <h1 class="text-4xl font-black text-foreground uppercase tracking-tighter italic">Messages</h1>
            <p class="text-[10px] font-black text-muted-foreground uppercase tracking-[0.3em] mt-2">Stay connected with your sellers and buyers</p>
        </div>
        <div class="md:ml-auto flex items-center gap-4">
            <div class="px-4 py-2 rounded-xl bg-primary/5 border border-primary/10 flex items-center gap-3">
                <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-[9px] font-black uppercase tracking-widest text-primary">Online</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {{-- Conversations List --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-black text-foreground uppercase tracking-[0.2em] flex items-center gap-3">
                    <i class="fa-solid fa-comments text-primary"></i> Your Chats
                </h3>
                <span class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest">{{ $conversations->count() }} contact(s)</span>
            </div>

            @forelse($conversations as $conversation)
                @php
                    $other = $conversation->getOtherUser(auth()->id());
                    $lastMessage = $conversation->lastMessage;
                    $unreadCount = $conversation->unreadMessagesCount(auth()->id());
                @endphp
                <a href="{{ route('chat.show', $conversation) }}" class="block group">
                    <div class="bg-card rounded-[2rem] border border-border p-6 shadow-sm hover:shadow-premium hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        @if($unreadCount > 0)
                            <div class="absolute top-0 right-0 h-20 w-20 bg-primary/5 rounded-bl-full -mr-10 -mt-10 group-hover:bg-primary/10 transition-colors"></div>
                        @endif
                        
                        <div class="flex items-center gap-6 relative z-10">
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-tr from-primary to-primary-hover rounded-2xl blur opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                <img src="{{ $other->avatar_url }}" class="relative h-16 w-16 rounded-2xl object-cover border border-border shadow-sm">
                                <div class="absolute -bottom-1 -right-1 h-4.5 w-4.5 bg-primary border-4 border-card rounded-full shadow-sm"></div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-2">
                                    <h4 class="text-sm font-black text-foreground uppercase tracking-tighter italic group-hover:text-primary transition-colors">{{ $other->name }}</h4>
                                    @if($conversation->last_message_at)
                                        <span class="text-[9px] font-black text-muted-foreground uppercase tracking-widest italic opacity-60">{{ $conversation->last_message_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-[11px] text-muted-foreground truncate italic max-w-[80%]">
                                        {{ $lastMessage ? $lastMessage->body : 'New chat started. Say hello!' }}
                                    </p>
                                    @if($unreadCount > 0)
                                        <span class="px-2 py-1 bg-primary text-white text-[9px] font-black rounded-lg shadow-lg shadow-primary/20 animate-pop">
                                            {{ $unreadCount }} NEW
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="h-12 w-12 flex items-center justify-center rounded-2xl bg-muted text-muted-foreground group-hover:text-primary group-hover:bg-primary/5 group-hover:rotate-12 transition-all">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="py-24 flex flex-col items-center justify-center bg-card rounded-[3rem] border border-border shadow-sm border-dashed">
                    <div class="h-20 w-20 bg-muted rounded-[2rem] flex items-center justify-center mb-6 text-muted-foreground/30">
                        <i class="fa-solid fa-message text-3xl"></i>
                    </div>
                    <p class="text-[11px] font-black text-muted-foreground uppercase tracking-[0.4em] italic">No messages yet</p>
                    <p class="text-[9px] font-bold text-muted-foreground/50 uppercase tracking-widest mt-2">Pick a contact to start chatting</p>
                </div>
            @endforelse
        </div>

        {{-- Start New Connection --}}
        <div class="lg:col-span-4">
            <div class="bg-card rounded-[2.5rem] border border-border p-8 shadow-premium sticky top-24">
                <h3 class="text-xs font-black text-foreground uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                    <i class="fa-solid fa-user-plus text-primary"></i> New Message
                </h3>

                <div class="space-y-3 max-h-[500px] overflow-y-auto custom-scrollbar pr-2">
                    @forelse($users as $user)
                        <form method="POST" action="{{ route('chat.start', $user) }}">
                            @csrf
                            <button type="submit" class="w-full text-left group">
                                <div class="flex items-center gap-4 p-4 rounded-2xl border border-transparent hover:border-border hover:bg-muted/50 transition-all">
                                    <div class="relative">
                                        <img src="{{ $user->avatar_url }}" class="h-10 w-10 rounded-xl object-cover shadow-sm">
                                        <div class="absolute inset-0 bg-primary/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-[10px] font-black text-foreground uppercase tracking-tighter truncate italic group-hover:text-primary transition-colors">{{ $user->name }}</div>
                                        <div class="text-[8px] font-black text-muted-foreground uppercase tracking-widest truncate opacity-60">{{ $user->email }}</div>
                                    </div>
                                    <div class="h-8 w-8 rounded-lg bg-muted flex items-center justify-center text-muted-foreground group-hover:bg-primary group-hover:text-white transition-all">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                    </div>
                                </div>
                            </button>
                        </form>
                    @empty
                        <p class="text-[9px] font-black text-muted-foreground uppercase tracking-widest italic text-center py-10">No users available</p>
                    @endforelse
                </div>
                
                <div class="mt-8 pt-8 border-t border-border">
                    <div class="p-4 bg-muted/30 rounded-2xl border border-border border-dashed">
                        <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest leading-relaxed text-center">
                            Stay safe and enjoy your shopping. Your privacy is our priority.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
