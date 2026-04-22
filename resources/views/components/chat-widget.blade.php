{{-- Floating Chat Widget Component --}}
@auth
<div x-data="{ 
    isOpen: false,
    messages: [
        { body: 'Hello! I am your AI Assistant 🤖\nHow can I help you today?', is_mine: false, sender: 'AI Assistant', time: '{{ now()->format('H:i') }}' }
    ],
    newMessage: '',
    isLoading: false,

    async sendMessage() {
        if (this.newMessage.trim() === '' || this.isLoading) return;

        const body = this.newMessage;
        this.newMessage = '';
        this.isLoading = true;

        // Push user message immediately
        this.messages.push({
            body: body,
            is_mine: true,
            sender: 'You',
            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        });

        this.scrollBottom();

        try {
            const response = await fetch('{{ route('chat.ask') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ body: body })
            });

            const data = await response.json();

            if (data.success) {
                this.messages.push({
                    body: data.ai_response.body,
                    is_mine: false,
                    sender: data.ai_response.sender,
                    time: data.ai_response.created_at
                });
            } else {
                this.messages.push({
                    body: data.message || 'Sorry, I could not process your request.',
                    is_mine: false,
                    sender: 'System',
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });
            }
            this.scrollBottom();
            this.isLoading = false;
        } catch (error) {
            console.error('Chat Failure:', error);
            this.messages.push({
                body: 'A network error occurred. Please try again.',
                is_mine: false,
                sender: 'System',
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
            this.scrollBottom();
            this.isLoading = false;
        }
    },

    scrollBottom() {
        this.$nextTick(() => {
            const container = this.$refs.messageContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    }
}" 
class="fixed bottom-8 right-8 z-[110]">

    {{-- Chat Window --}}
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-90 translate-y-10"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-90 translate-y-10"
         class="absolute bottom-20 right-0 w-[350px] sm:w-[400px] bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-[0_20px_60px_rgba(0,0,0,0.15)] dark:shadow-[0_20px_60px_rgba(0,0,0,0.5)] overflow-hidden flex flex-col"
         style="display: none;">
        
        {{-- Header --}}
        <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-primary rounded-2xl flex items-center justify-center text-primary-foreground shadow-lg shadow-primary/30">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">AI Assistant</h3>
                    <div class="flex items-center gap-1.5">
                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Always Online</span>
                    </div>
                </div>
            </div>
            <button @click="isOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Messages Area --}}
        <div x-ref="messageContainer" class="flex-1 p-6 overflow-y-auto max-h-[400px] min-h-[300px] space-y-4 custom-scrollbar bg-slate-50/30 dark:bg-slate-900/30">
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.is_mine ? 'flex flex-col items-end' : 'flex flex-col items-start'">
                    <div :class="msg.is_mine ? 'bg-primary text-primary-foreground rounded-2xl rounded-tr-none shadow-lg shadow-primary/10' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-2xl rounded-tl-none border border-slate-100 dark:border-slate-700 shadow-sm'"
                         class="px-4 py-3 max-w-[85%] text-xs font-medium italic leading-relaxed whitespace-pre-line">
                        <span x-text="msg.body"></span>
                    </div>
                    <div class="mt-1 flex items-center gap-2 text-[8px] font-black uppercase tracking-widest text-slate-400 italic">
                        <span x-text="msg.sender"></span>
                        <span class="opacity-30">•</span>
                        <span x-text="msg.time"></span>
                    </div>
                </div>
            </template>
            
            {{-- Typing Indicator --}}
            <div x-show="isLoading" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="flex flex-col items-start" style="display: none;">
                <div class="bg-white dark:bg-slate-800 px-4 py-3 rounded-2xl rounded-tl-none border border-slate-100 dark:border-slate-700 shadow-sm flex items-center gap-2">
                    <span class="text-slate-500 dark:text-slate-400 text-xs italic">We’re typing…</span>
                    <div class="flex gap-1 items-center">
                        <span class="w-1 h-1 bg-primary rounded-full animate-bounce [animation-duration:0.6s]"></span>
                        <span class="w-1 h-1 bg-primary rounded-full animate-bounce [animation-duration:0.6s] [animation-delay:0.1s]"></span>
                        <span class="w-1 h-1 bg-primary rounded-full animate-bounce [animation-duration:0.6s] [animation-delay:0.2s]"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-950">
            <div class="relative flex items-center gap-2">
                <input type="text" 
                       x-model="newMessage"
                       @keydown.enter="sendMessage()"
                       class="flex-1 px-5 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-xs font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none dark:text-white placeholder-slate-400"
                       placeholder="Ask about your order, products, or anything…">
                <button @click="sendMessage()"
                        class="h-10 w-10 rounded-xl bg-primary text-primary-foreground flex items-center justify-center shadow-lg shadow-primary/30 hover:scale-110 active:scale-95 transition-all">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Floating Toggle Button --}}
    <button @click="isOpen = !isOpen; if(isOpen) scrollBottom()"
            class="h-16 w-16 rounded-[2rem] bg-white border-2 border-primary text-primary shadow-[0_15px_40px_rgba(230,6,122,0.2)] flex items-center justify-center hover:scale-110 active:scale-95 transition-all group relative">
        <div x-show="!isOpen" x-transition class="absolute inset-0 flex items-center justify-center">
            <i class="fa-solid fa-comment-dots text-2xl group-hover:rotate-12 transition-transform text-primary"></i>
        </div>
        <div x-show="isOpen" x-transition class="absolute inset-0 flex items-center justify-center" style="display: none;">
            <i class="fa-solid fa-xmark text-2xl text-primary"></i>
        </div>
        {{-- Tooltip --}}
        <div x-show="!isOpen" class="absolute right-20 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest px-4 py-2 rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all pointer-events-none">
            AI Chat
        </div>
    </button>
</div>
@endauth
