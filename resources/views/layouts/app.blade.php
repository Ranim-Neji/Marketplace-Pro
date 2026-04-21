<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-authenticated" content="{{ auth()->check() ? '1' : '0' }}">
    <meta name="notifications-fetch-url" content="{{ auth()->check() ? url('/notifications') : '' }}">
    <meta name="notifications-poll-ms" content="5000">
    <meta name="user-id" content="{{ auth()->id() }}">
    <title>@yield('title', config('app.name', 'MarketPlace Pro'))</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Scripts/Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-foreground transition-colors duration-300 relative bg-background">
    <x-background-gradient />

    <div class="relative z-10 flex flex-col min-h-screen">
        <x-navbar />

    {{-- TOAST SYSTEM --}}
    <div x-data="{ 
        toasts: [],
        add(toast) {
            this.toasts.push({
                id: Date.now(),
                type: toast.type || 'info',
                message: toast.message,
                show: true
            });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== this.toasts[0]?.id);
            }, 5000);
        }
    }" 
    @toast.window="add($event.detail)"
    class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        
        {{-- Flash Messages --}}
        @foreach(['success', 'error', 'info', 'warning'] as $type)
            @if(session($type))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                    class="pointer-events-auto p-4 rounded-lg shadow-premium border flex items-start gap-3 animate-fade-in bg-card border-border">
                    <div class="mt-0.5">
                        @if($type === 'success') <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-primary"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" /></svg>
                        @elseif($type === 'error') <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-primary"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" /></svg>
                        @else <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-primary"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" /></svg>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-foreground flex-1">{{ session($type) }}</p>
                    <button @click="show = false" class="text-muted-foreground hover:text-foreground transition-colors"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg></button>
                </div>
            @endif
        @endforeach

        {{-- Dynamic Toasts --}}
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" x-transition class="pointer-events-auto p-4 rounded-lg shadow-premium border flex items-start gap-3 bg-card border-border animate-fade-in">
                <p class="text-sm font-medium text-foreground flex-1" x-text="toast.message"></p>
                <button @click="toast.show = false" class="text-muted-foreground hover:text-foreground transition-colors"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg></button>
            </div>
        </template>
    </div>

    {{-- MAIN --}}
    <main class="flex-grow flex flex-col">
        @yield('content')
    </main>

    <x-chat-widget />
    <x-cart-sidebar />

    {{-- Search Overlay --}}
    <div x-data="{ 
            open: false,
            init() {
                window.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                        e.preventDefault();
                        this.open = true;
                        this.$nextTick(() => this.$refs.searchInput.focus());
                    }
                });
            }
         }" 
         @keydown.window.escape="open = false"
         @open-search.window="open = true; $nextTick(() => $refs.searchInput.focus())"
         x-show="open" 
         class="fixed inset-0 z-[100] overflow-y-auto p-4 sm:p-6 md:p-20" 
         style="display: none;">
        
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-background/50 backdrop-blur-sm transition-opacity" @click="open = false"></div>

        <div x-show="open" x-transition class="relative mx-auto max-w-2xl transform divide-y divide-border overflow-hidden rounded-xl bg-card shadow-premium border border-border transition-all mt-10">
            <div class="relative flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 pointer-events-none absolute left-5 text-muted-foreground"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input id="liveSearch" x-ref="searchInput" type="text" class="h-14 w-full border-0 bg-transparent pl-12 pr-16 text-foreground placeholder:text-muted-foreground focus:ring-0 text-sm font-medium outline-none" placeholder="Search products..." role="combobox" aria-expanded="false" aria-controls="options">
                <div class="absolute right-4 text-[10px] font-bold text-muted-foreground bg-muted px-2 py-1 rounded border border-border">Ctrl K</div>
            </div>

            <div id="searchResults" class="max-h-96 scroll-py-3 overflow-y-auto p-3 hidden custom-scrollbar">
                {{-- Results injected via JS --}}
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="bg-card border-t border-border py-12 mt-20">
        <div class="container-layout">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-foreground tracking-tight font-serif italic">MarketPlace Pro</span>
                    </div>
                    <p class="text-sm text-muted-foreground leading-relaxed max-w-xs">
                        A modern multi-vendor marketplace built for speed, simplicity, and premium experiences.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-foreground mb-4 font-mono">Marketplace</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('catalog.index') }}" class="text-muted-foreground hover:text-primary transition-colors">Catalogue</a></li>
                        <li><a href="#" class="text-muted-foreground hover:text-primary transition-colors">Categories</a></li>
                        <li><a href="#" class="text-muted-foreground hover:text-primary transition-colors">Vendors</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-foreground mb-6 font-mono">Support</h4>
                    <div class="space-y-4">
                        <a href="#" class="inline-flex items-center justify-center px-6 py-3 bg-primary text-primary-foreground rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                            Contact Support
                        </a>
                        <ul class="space-y-3 text-[11px] font-medium">
                            <li><a href="#" class="text-muted-foreground hover:text-primary transition-colors italic">Terms of Service</a></li>
                            <li><a href="#" class="text-muted-foreground hover:text-primary transition-colors italic">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-foreground mb-4 font-mono">Connect</h4>
                    <div class="flex gap-3 mb-6">
                        <a href="#" class="h-9 w-9 rounded-lg border border-border flex items-center justify-center text-muted-foreground hover:text-primary hover:bg-accent transition-all"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg></a>
                        <a href="#" class="h-9 w-9 rounded-lg border border-border flex items-center justify-center text-muted-foreground hover:text-primary hover:bg-accent transition-all"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg></a>
                    </div>
                    @auth
                        <form method="POST" action="{{ route('logout', absolute: false) }}">
                            @csrf
                            <button type="submit" class="text-xs font-black uppercase tracking-[0.2em] text-rose-500 hover:text-rose-600 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-power-off"></i>
                                Sign Out
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-border flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-muted-foreground">© {{ date('Y') }} MarketPlace Pro. All rights reserved.</p>
            </div>
        </div>
    </footer>
    </div>

    @stack('scripts')
</body>
</html>
