@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name)

@section('content')
<div class="container-layout py-8 h-[calc(100vh-4rem)] flex flex-col">
    {{-- Chat Header --}}
    <div class="flex items-center justify-between mb-6 bg-card/50 backdrop-blur-md p-6 rounded-3xl border border-border shadow-premium">
        <div class="flex items-center gap-5">
            <a href="{{ route('chat.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-muted hover:bg-primary/10 text-muted-foreground hover:text-primary transition-all active:scale-90">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-tr from-primary to-primary-hover rounded-2xl blur opacity-25 group-hover:opacity-40 transition-opacity"></div>
                <img src="{{ $otherUser->avatar_url }}" class="relative h-12 w-12 rounded-2xl object-cover border-2 border-background shadow-sm">
                <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-primary border-2 border-background rounded-full shadow-sm animate-pulse"></div>
            </div>
            <div>
                <h2 class="text-sm font-black text-foreground uppercase tracking-tighter italic flex items-center gap-2">
                    {{ $otherUser->name }}
                    <span class="text-[8px] px-2 py-0.5 rounded-full bg-primary/10 text-primary font-black uppercase tracking-widest border border-primary/20">Verified</span>
                </h2>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="text-[9px] font-black text-primary uppercase tracking-widest flex items-center gap-1.5">
                        <span class="h-1 w-1 bg-primary rounded-full animate-ping"></span>
                        Online
                    </span>
                </div>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-3">
            <div class="px-4 py-2 rounded-xl bg-muted/50 border border-border text-[9px] font-black text-muted-foreground uppercase tracking-[0.2em] italic font-mono">
                Secure Connection
            </div>
        </div>
    </div>

    {{-- Message Container --}}
    <div class="flex-1 bg-card rounded-[2.5rem] border border-border shadow-premium overflow-hidden flex flex-col mb-4">
        <div id="chatMessages" class="flex-1 p-8 overflow-y-auto space-y-6 custom-scrollbar scroll-smooth">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} group/msg" data-message-id="{{ $message->id }}">
                    <div class="max-w-[80%] lg:max-w-[70%] flex flex-col {{ $message->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                        <div class="px-6 py-4 rounded-[2rem] text-sm font-medium leading-relaxed italic shadow-sm transition-all duration-300 {{ $message->sender_id === auth()->id() ? 'bg-primary text-white rounded-tr-none hover:shadow-lg hover:shadow-primary/20' : 'bg-muted text-foreground rounded-tl-none border border-border hover:bg-muted/80' }}">
                            {{ $message->body }}
                        </div>
                        <div class="mt-2 flex items-center gap-3 text-[9px] font-black uppercase tracking-widest text-muted-foreground italic opacity-0 group-hover/msg:opacity-100 transition-opacity {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                            <span class="text-foreground/60">{{ $message->sender->name }}</span>
                            <span class="opacity-20">•</span>
                            <span>{{ $message->created_at->format('H:i') }}</span>
                            @if($message->sender_id === auth()->id())
                                <span class="text-primary/60"><i class="fa-solid fa-check-double text-[8px]"></i> Delivered</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div id="emptyState" class="h-full flex flex-col items-center justify-center opacity-20 select-none">
                    <div class="h-20 w-20 rounded-[2.5rem] bg-muted flex items-center justify-center mb-6">
                        <i class="fa-solid fa-message text-4xl text-primary animate-pulse"></i>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.4em]">No messages yet</p>
                    <p class="text-[8px] font-bold uppercase tracking-widest mt-2 opacity-50">Say hello to start the conversation.</p>
                </div>
            @endforelse
        </div>

        {{-- Chat Input --}}
        <div class="p-6 bg-muted/30 border-t border-border">
            <form id="chatForm" method="POST" action="{{ route('chat.send', $conversation) }}">
                @csrf
                <div class="relative flex items-center gap-4">
                    <div class="flex-1 relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-primary to-primary-hover rounded-2xl blur opacity-0 group-focus-within:opacity-10 transition-opacity"></div>
                        <input id="chatInput" 
                               type="text" 
                               name="body" 
                               class="relative w-full pl-6 pr-24 py-5 bg-card border border-border rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all placeholder-muted-foreground/50 italic" 
                               placeholder="Type a message..." 
                               maxlength="1000" 
                               required 
                               autocomplete="off">
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center gap-3">
                            <span class="text-[9px] font-black text-muted-foreground/30 uppercase tracking-[0.2em] font-mono select-none">Enter</span>
                        </div>
                    </div>
                    <button class="h-16 w-16 rounded-2xl bg-primary text-white flex items-center justify-center shadow-premium-lg hover:shadow-primary/30 hover:-translate-y-1 active:translate-y-0.5 active:scale-95 transition-all group overflow-hidden relative" type="submit">
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/0 to-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <i class="fa-solid fa-paper-plane text-lg group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </button>
                </div>
            </form>
            <div class="mt-4 flex justify-center">
                <p class="text-[8px] font-black text-muted-foreground/40 uppercase tracking-[0.3em] font-mono">Your messages are private and secure</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const conversationId = {{ $conversation->id }};
    const currentUserId = {{ auth()->id() }};
    const messagesEl = document.getElementById('chatMessages');
    const formEl = document.getElementById('chatForm');
    const inputEl = document.getElementById('chatInput');
    const seenIds = new Set(Array.from(messagesEl.querySelectorAll('[data-message-id]')).map(el => Number(el.dataset.messageId)));

    function lastMessageId() {
        let max = 0;
        seenIds.forEach(id => { if (id > max) max = id; });
        return max;
    }

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function renderMessage(msg) {
        if (seenIds.has(Number(msg.id))) return;
        seenIds.add(Number(msg.id));

        const emptyState = document.getElementById('emptyState');
        if (emptyState) emptyState.remove();

        const wrapper = document.createElement('div');
        const isMine = msg.is_mine || Number(msg.sender_id) === currentUserId;
        wrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'} group/msg animate-pop`;
        wrapper.dataset.messageId = msg.id;

        const bubbleClass = isMine 
            ? 'bg-primary text-white rounded-[2rem] rounded-tr-none shadow-sm hover:shadow-lg hover:shadow-primary/20' 
            : 'bg-muted text-foreground rounded-[2rem] rounded-tl-none border border-border hover:bg-muted/80';
        
        const safeBody = String(msg.body).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        
        wrapper.innerHTML = `
            <div class="max-w-[80%] lg:max-w-[70%] flex flex-col ${isMine ? 'items-end' : 'items-start'}">
                <div class="px-6 py-4 rounded-[2rem] text-sm font-medium leading-relaxed italic shadow-sm transition-all duration-300 ${bubbleClass}">
                    ${safeBody}
                </div>
                <div class="mt-2 flex items-center gap-3 text-[9px] font-black uppercase tracking-widest text-muted-foreground italic opacity-0 group-hover/msg:opacity-100 transition-opacity ${isMine ? 'flex-row-reverse' : ''}">
                    <span class="text-foreground/60">${msg.sender}</span>
                    <span class="opacity-20">•</span>
                    <span>${msg.created_at}</span>
                    ${isMine ? '<span class="text-primary/60"><i class="fa-solid fa-check-double text-[8px]"></i> Delivered</span>' : ''}
                </div>
            </div>
        `;

        messagesEl.appendChild(wrapper);
        scrollToBottom();
    }

    async function pollMessages() {
        try {
            const response = await fetch(`{{ route('chat.messages', $conversation) }}?since=${lastMessageId()}`, {
                headers: {'X-Requested-With': 'XMLHttpRequest'},
            });
            if (!response.ok) return;

            const data = await response.json();
            data.forEach(renderMessage);
        } catch (error) {
            console.error('Message polling failed', error);
        }
    }

    formEl.addEventListener('submit', async function (event) {
        event.preventDefault();
        const body = inputEl.value.trim();
        if(!body) return;

        const formData = new FormData(formEl);
        inputEl.value = '';

        try {
            const response = await fetch(formEl.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            });

            if (!response.ok) return;

            const payload = await response.json();
            if (payload.success && payload.message) {
                renderMessage({ ...payload.message, is_mine: true });
                inputEl.focus();
            }
        } catch (error) {
            console.error('Sending message failed', error);
        }
    });

    scrollToBottom();
    setInterval(pollMessages, 3000);
})();
</script>
@endpush
