@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="container-layout py-12 h-[calc(100vh-8rem)] flex flex-col">
    {{-- Chat Header --}}
    <div class="flex items-center justify-between mb-8 bg-white dark:bg-slate-950 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('chat.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-primary transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div class="relative">
                <img src="{{ $otherUser->avatar_url }}" class="h-12 w-12 rounded-2xl object-cover border border-slate-100 dark:border-slate-800">
                <div class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 bg-emerald-500 border-2 border-white dark:border-slate-950 rounded-full"></div>
            </div>
            <div>
                <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Conversation with {{ $otherUser->name }}</h2>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">Active Transmission</span>
                </div>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-3">
            <div class="px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-[9px] font-black text-slate-400 uppercase tracking-widest italic">
                Encrypted Node
            </div>
        </div>
    </div>

    {{-- Message Container --}}
    <div class="flex-1 bg-white dark:bg-slate-950 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col mb-6">
        <div id="chatMessages" class="flex-1 p-8 overflow-y-auto space-y-6 custom-scrollbar scroll-smooth">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                    <div class="max-w-[75%] lg:max-w-[60%]">
                        <div class="px-5 py-4 rounded-3xl text-xs font-medium leading-relaxed italic shadow-sm {{ $message->sender_id === auth()->id() ? 'bg-primary text-white rounded-tr-none' : 'bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-tl-none border border-slate-100 dark:border-slate-800' }}">
                            {{ $message->body }}
                        </div>
                        <div class="mt-2 flex items-center gap-3 text-[8px] font-black uppercase tracking-widest text-slate-400 italic {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <span>{{ $message->sender->name }}</span>
                            <span class="opacity-30">•</span>
                            <span>{{ $message->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div id="emptyState" class="h-full flex flex-col items-center justify-center opacity-20">
                    <i class="fa-solid fa-satellite-dish text-6xl mb-6"></i>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em]">Establishing secure connection...</p>
                </div>
            @endforelse
        </div>

        {{-- Chat Input --}}
        <div class="p-6 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
            <form id="chatForm" method="POST" action="{{ route('chat.send', $conversation) }}">
                @csrf
                <div class="relative flex items-center gap-4">
                    <div class="flex-1 relative">
                        <input id="chatInput" 
                               type="text" 
                               name="body" 
                               class="w-full pl-6 pr-12 py-4 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all dark:text-white placeholder-slate-400 italic" 
                               placeholder="Type your signal..." 
                               maxlength="1000" 
                               required 
                               autocomplete="off">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-slate-300 uppercase tracking-widest">
                            Ctrl Enter
                        </div>
                    </div>
                    <button class="h-14 w-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all group" type="submit">
                        <i class="fa-solid fa-paper-plane text-sm group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </button>
                </div>
            </form>
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
        wrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'} animate-fade-in`;
        wrapper.dataset.messageId = msg.id;

        const bubbleClass = isMine ? 'bg-primary text-white rounded-tr-none' : 'bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 rounded-tl-none border border-slate-100 dark:border-slate-800';
        const safeBody = String(msg.body).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        
        wrapper.innerHTML = `
            <div class="max-w-[75%] lg:max-w-[60%]">
                <div class="px-5 py-4 rounded-3xl text-xs font-medium leading-relaxed italic shadow-sm ${bubbleClass}">
                    ${safeBody}
                </div>
                <div class="mt-2 flex items-center gap-3 text-[8px] font-black uppercase tracking-widest text-slate-400 italic ${isMine ? 'justify-end' : 'justify-start'}">
                    <span>${msg.sender}</span>
                    <span class="opacity-30">•</span>
                    <span>${msg.created_at}</span>
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
        const formData = new FormData(formEl);

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
                formEl.reset();
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
