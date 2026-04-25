<nav x-data="{ 
    isScrolled: false,
    mobileMenuOpen: false,
    init() {
        window.addEventListener('scroll', () => {
            this.isScrolled = window.scrollY > 10;
        });
    }
}" 
:class="isScrolled ? 'bg-background/80 backdrop-blur-lg border-b border-border shadow-sm' : 'bg-background/60 backdrop-blur-md border-b border-transparent'"
class="sticky top-0 inset-x-0 z-50 h-16 transition-all duration-300">
    
    <div class="container-layout h-full flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="h-8 w-8 rounded-md flex items-center justify-center transition-transform group-hover:scale-105 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="MarketPlace Logo" class="w-full h-full object-cover">
            </div>
            <span class="font-semibold text-sm text-foreground tracking-tight font-serif italic">MarketPlace Pro</span>
        </a>

        {{-- Desktop Nav --}}
        <div class="hidden md:flex items-center justify-center absolute left-1/2 -translate-x-1/2">
            <x-nav-header />
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 sm:gap-3">
            <button @click="$dispatch('open-search')" class="h-8 w-8 flex items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            </button>

            @auth
                <a href="{{ route('wishlist.index') }}" class="h-8 w-8 flex items-center justify-center rounded-md text-muted-foreground hover:bg-warning/10 hover:text-warning transition-colors" title="Wishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                </a>

                @php
                    $unreadMessages = auth()->user()->unreadMessagesCount();
                @endphp
                <a href="{{ route('chat.index') }}" class="relative h-8 w-8 flex items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-all duration-200" title="Messages">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.028Z" /></svg>
                    <span id="unreadMessagesBadge" class="absolute -top-1 -right-1 bg-primary text-primary-foreground text-[8px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-background shadow-sm {{ $unreadMessages > 0 ? '' : 'hidden' }}">
                        <span id="unreadMessagesCount">{{ $unreadMessages }}</span>
                    </span>
                </a>

                <button @click.prevent="$dispatch('open-cart')" class="relative h-8 w-8 flex items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                    @php 
                        $cartCount = Cache::remember('cart_count_'.auth()->id(), 60, function() {
                            return auth()->user()->cart()->withSum('items', 'quantity')->first()?->items_sum_quantity ?? 0;
                        });
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-primary text-primary-foreground text-[8px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-background shadow-sm">{{ $cartCount }}</span>
                    @endif
                </button>

                <div id="notificationWidget" class="relative" x-data="{ open: false }">
                    <button id="notificationBell" @click="open = !open" type="button" class="h-8 w-8 flex items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-all duration-200 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 group-hover:rotate-12 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        <span id="notificationBadge" class="absolute -top-1 -right-1 bg-primary text-primary-foreground text-[8px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-background shadow-sm {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}">
                            <span id="notificationCount">{{ auth()->user()->unreadNotifications->count() }}</span>
                        </span>
                    </button>

                    <div id="notificationDropdown" 
                         x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-80 bg-card border border-border rounded-2xl shadow-premium overflow-hidden z-50"
                         style="display: none;">
                        <div class="px-5 py-4 border-b border-border flex justify-between items-center bg-muted/20">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-foreground font-mono">Notifications</h3>
                            <a href="{{ route('notifications.index') }}" class="text-[9px] font-black text-primary hover:text-primary/80 uppercase tracking-widest font-mono transition-colors">View All</a>
                        </div>
                        <div id="notificationList" class="max-h-96 overflow-y-auto divide-y divide-border custom-scrollbar">
                            <div class="p-8 text-center opacity-50">
                                <i class="fa-solid fa-spinner fa-spin text-primary mb-2"></i>
                                <p class="text-[10px] font-bold uppercase tracking-widest">Synchronizing...</p>
                            </div>
                        </div>
                        <div class="px-5 py-3 border-t border-border bg-muted/10">
                            <button onclick="markAllAsRead()" class="w-full text-center text-[9px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-colors">
                                Mark all as read
                            </button>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="h-8 w-8 rounded-md border border-border p-0.5 overflow-hidden hover:border-primary transition-colors" title="Settings">
                    <img src="{{ auth()->user()->avatar_url }}" class="h-full w-full rounded-[4px] object-cover">
                </a>

                <form method="POST" action="{{ route('logout', absolute: false) }}" class="inline">
                    @csrf
                    <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-md text-muted-foreground hover:bg-warning/10 hover:text-warning transition-colors" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                    </button>
                </form>
            @else
                <div class="hidden sm:flex items-center gap-2">
                    <a href="{{ route('login') }}" class="btn-ghost px-3 py-1.5 text-xs font-medium text-muted-foreground hover:text-foreground transition-colors rounded-md">Log in</a>
                    <a href="{{ route('register') }}" class="bg-primary text-primary-foreground px-3 py-1.5 text-xs font-medium rounded-md hover:opacity-90 transition-colors shadow-sm">Sign up</a>
                </div>
            @endauth

            {{-- Mobile Menu Toggle --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden h-8 w-8 flex items-center justify-center rounded-md text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-colors">
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Drawer --}}
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         x-cloak
         class="md:hidden bg-background border-b border-border absolute inset-x-0 top-16 shadow-premium z-40 overflow-hidden">
        <div class="container-layout py-8 space-y-6">
            <nav class="flex flex-col gap-4">
                <a href="{{ route('home') }}" class="text-lg font-black uppercase tracking-tighter italic {{ request()->routeIs('home') ? 'text-primary' : 'text-foreground' }}">Home</a>
                <a href="{{ route('catalog.index') }}" class="text-lg font-black uppercase tracking-tighter italic {{ request()->routeIs('catalog.*') ? 'text-primary' : 'text-foreground' }}">Shop</a>
                <a href="{{ route('services.index') }}" class="text-lg font-black uppercase tracking-tighter italic {{ request()->routeIs('services.index') ? 'text-primary' : 'text-foreground' }}">Services</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-lg font-black uppercase tracking-tighter italic text-primary">Admin Panel</a>
                    @elseif(auth()->user()->isVendor())
                        <a href="{{ route('vendor.dashboard') }}" class="text-lg font-black uppercase tracking-tighter italic text-primary">Vendor Hub</a>
                    @endif
                    <a href="{{ route('orders.index') }}" class="text-lg font-black uppercase tracking-tighter italic">My Orders</a>
                    <a href="{{ route('wishlist.index') }}" class="text-lg font-black uppercase tracking-tighter italic {{ request()->routeIs('wishlist.*') ? 'text-primary' : 'text-foreground' }}">Saved Wishlist</a>
                    <a href="{{ route('profile.edit') }}" class="text-lg font-black uppercase tracking-tighter italic">Settings</a>
                    
                    <form method="POST" action="{{ route('logout', absolute: false) }}" class="pt-4 border-t border-border">
                        @csrf
                        <button type="submit" class="text-lg font-black uppercase tracking-tighter italic text-warning flex items-center gap-2">
                            <i class="fa-solid fa-power-off text-sm"></i>
                            Logout Session
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-border">
                        <a href="{{ route('login') }}" class="btn-secondary py-3 text-center text-xs">Log In</a>
                        <a href="{{ route('register') }}" class="btn-primary py-3 text-center text-xs">Sign Up</a>
                    </div>
                @endauth
            </nav>
        </div>
    </div>
</nav>
