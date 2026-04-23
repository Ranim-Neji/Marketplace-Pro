<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-authenticated" content="{{ auth()->check() ? '1' : '0' }}">
    <meta name="notifications-fetch-url" content="{{ auth()->check() ? url('/notifications') : '' }}">
    <meta name="notifications-poll-ms" content="5000">
    <title>@yield('title', 'Admin Panel') | MarketPlace Pro</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@500;600;700;800;900&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    {{-- Scripts/Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="min-h-screen font-['Inter'] text-foreground bg-background antialiased transition-colors duration-500 overflow-x-hidden relative">
    <x-background-gradient />

    <div x-data="{
        toasts: [],
        add(toast) {
            this.toasts.push({
                id: Date.now() + Math.random(),
                type: toast.type || 'info',
                message: toast.message || 'Notification',
                show: true
            });
            setTimeout(() => {
                this.toasts = this.toasts.filter((item) => item.id !== this.toasts[0]?.id);
            }, 5000);
        }
    }"
    @toast.window="add($event.detail)"
    class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" x-transition class="pointer-events-auto p-4 rounded-lg shadow-premium border flex items-start gap-3 bg-card border-border animate-fade-in">
                <p class="text-sm font-medium text-foreground flex-1" x-text="toast.message"></p>
                <button @click="toast.show = false" class="text-muted-foreground hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>
    
    <div class="flex min-h-screen relative z-10">
        {{-- Sidebar --}}
        <aside class="w-72 bg-card/60 backdrop-blur-xl border-r border-border hidden lg:flex flex-col sticky top-0 h-screen">
            <div class="p-8 border-b border-border">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-premium">
                        <i class="fa-solid fa-user-shield text-lg"></i>
                    </div>
                    <span class="text-lg font-black tracking-tighter uppercase italic font-serif">Admin<span class="text-primary">Panel</span></span>
                </a>
            </div>

            <nav class="flex-1 p-6 space-y-2 overflow-y-auto custom-scrollbar">
                <div class="text-[9px] font-black text-muted-foreground uppercase tracking-[0.2em] mb-4 mt-6 px-4">Management</div>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-premium' : 'text-muted-foreground hover:bg-accent hover:text-foreground transition-all' }}">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Global Dashboard</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-white shadow-premium' : 'text-muted-foreground hover:bg-accent hover:text-foreground transition-all' }}">
                    <i class="fa-solid fa-truck-fast text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Global Orders</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white shadow-premium' : 'text-muted-foreground hover:bg-accent hover:text-foreground transition-all' }}">
                    <i class="fa-solid fa-id-card-clip text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">User Management</span>
                </a>

                <div class="text-[9px] font-black text-muted-foreground uppercase tracking-[0.2em] mb-4 mt-10 px-4">Inventory</div>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('admin.categories.*') ? 'bg-primary text-white shadow-premium' : 'text-muted-foreground hover:bg-accent hover:text-foreground transition-all' }}">
                    <i class="fa-solid fa-sitemap text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Categories</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('admin.products.*') ? 'bg-primary text-white shadow-premium' : 'text-muted-foreground hover:bg-accent hover:text-foreground transition-all' }}">
                    <i class="fa-solid fa-cubes-stacked text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Products</span>
                </a>

                <div class="text-[9px] font-black text-muted-foreground uppercase tracking-[0.2em] mb-4 mt-10 px-4">Public Site</div>

                <a href="{{ route('home') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-muted-foreground hover:bg-accent hover:text-foreground transition-all">
                    <i class="fa-solid fa-eye text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">View Store</span>
                </a>
            </nav>

            <div class="p-6 border-t border-border">
                <div class="bg-accent rounded-2xl p-4 flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar_url }}" class="h-10 w-10 rounded-xl border border-card shadow-sm">
                    <div class="min-w-0 flex-1">
                        <div class="text-[10px] font-black text-foreground uppercase tracking-tighter truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[8px] font-bold text-muted-foreground uppercase tracking-widest">Administrator</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col min-w-0 max-h-screen overflow-y-auto custom-scrollbar">
            {{-- Header --}}
            <header class="h-24 bg-card/40 backdrop-blur-xl border-b border-border sticky top-0 z-30 flex items-center justify-between px-10">
                <div class="flex items-center gap-6">
                    <button class="lg:hidden h-12 w-12 rounded-xl bg-accent flex items-center justify-center text-muted-foreground">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    <div>
                        <h2 class="text-sm font-black text-foreground uppercase tracking-[0.2em] italic font-serif">@yield('title', 'Admin Dashboard')</h2>
                        <div class="text-[8px] font-bold text-muted-foreground uppercase tracking-widest mt-1">System Status: <span class="text-primary font-black animate-pulse">ACTIVE</span></div>
                    </div>
                    <button id="themeToggle" class="h-10 w-10 ml-4 rounded-xl bg-accent border border-border text-muted-foreground hover:text-primary transition-all shadow-sm">
                        <i class="fa-solid fa-moon dark:hidden text-xs"></i>
                        <i class="fa-solid fa-sun hidden dark:block text-xs"></i>
                    </button>
                </div>

                <div class="flex items-center gap-4">
                    <div id="notificationWidget" class="relative">
                        <button id="notificationBell" type="button" class="h-12 w-12 rounded-2xl bg-accent border border-border text-muted-foreground hover:text-primary transition-all flex items-center justify-center shadow-sm relative">
                            <i class="fa-regular fa-bell"></i>
                            <span id="notificationBadge" class="absolute top-2 right-2 min-w-[14px] h-[14px] px-1 bg-primary text-primary-foreground text-[8px] font-bold rounded-full ring-2 ring-background flex items-center justify-center {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}">
                                <span id="notificationCount">{{ auth()->user()->unreadNotifications->count() }}</span>
                            </span>
                        </button>
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-card border border-border rounded-xl shadow-premium overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-border flex justify-between items-center bg-muted/30">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-foreground font-mono">Notifications</h3>
                                <a href="{{ route('notifications.index') }}" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-widest font-mono">View All</a>
                            </div>
                            <div id="notificationList" class="max-h-96 overflow-y-auto divide-y divide-border"></div>
                        </div>
                    </div>
                    <div class="h-px w-4 bg-border hidden sm:block"></div>
                    <form method="POST" action="{{ route('logout', absolute: false) }}">
                        @csrf
                        <button class="h-12 px-6 rounded-2xl border border-warning/20 text-warning text-[10px] font-black uppercase tracking-widest hover:bg-warning/10 transition-all italic">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Dashboard Content --}}
            <div class="p-10">
                @if(session('success'))
                    <div class="mb-10 p-6 rounded-3xl bg-primary/10 border border-primary/20 flex items-center gap-4 animate-slide-up">
                        <div class="h-10 w-10 bg-primary rounded-xl flex items-center justify-center text-white">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="text-[10px] font-black text-primary uppercase tracking-widest italic">{{ session('success') }}</div>
                    </div>
                @endif

                @yield('content')
            </div>

            <footer class="mt-auto p-10 border-t border-border flex flex-col md:flex-row justify-between items-center gap-6 bg-card/20 backdrop-blur-sm">
                <div class="text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">© {{ date('Y') }} MarketPlace Pro Admin • V1.0</div>
                <div class="flex items-center gap-8 text-[9px] font-black text-muted-foreground uppercase tracking-widest italic">
                    <a href="#" class="hover:text-primary transition-colors">Documentation</a>
                    <a href="mailto:support@marketplace-pro.com" class="hover:text-primary transition-colors">Support Center</a>
                    <form method="POST" action="{{ route('logout', absolute: false) }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-warning transition-colors uppercase">Sign Out</button>
                    </form>
                </div>
            </footer>
        </main>
    </div>

    @stack('scripts')
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; }
        @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-up { animation: slide-up 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>
    <script>
        // Theme Engine
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        document.getElementById('themeToggle')?.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });

        // Notification System
        const bell = document.getElementById('notificationBell');
        const dropdown = document.getElementById('notificationDropdown');
        
        bell?.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown?.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!dropdown?.contains(e.target) && !bell?.contains(e.target)) {
                dropdown?.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
