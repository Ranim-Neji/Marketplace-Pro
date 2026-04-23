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
 
    {{-- ============================================================ --}}
    {{-- AJAX SMART SEARCH OVERLAY                                     --}}
    {{-- ============================================================ --}}
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
         @keydown.window.escape="open = false; smartSearch.close()"
         @open-search.window="open = true; $nextTick(() => { $refs.searchInput.focus(); smartSearch.init($refs.searchInput); })"
         x-show="open"
         class="fixed inset-0 z-[100] overflow-y-auto p-4 sm:p-6 md:p-20"
         style="display: none;">
 
        {{-- Backdrop --}}
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-background/50 backdrop-blur-sm transition-opacity" @click="open = false; smartSearch.close()"></div>
 
        {{-- Modal --}}
        <div x-show="open" x-transition class="relative mx-auto max-w-2xl transform divide-y divide-border overflow-hidden rounded-xl bg-card shadow-premium border border-border transition-all mt-10">
 
            {{-- Input row --}}
            <div class="relative flex items-center">
 
                {{-- Loupe (visible par défaut) --}}
                <svg id="searchIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="w-4 h-4 pointer-events-none absolute left-5 text-muted-foreground transition-opacity duration-200">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
 
                {{-- Spinner (caché par défaut, affiché pendant la requête) --}}
                <svg id="searchSpinner" class="w-4 h-4 absolute left-5 text-primary animate-spin hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
 
                <input id="liveSearch"
                       x-ref="searchInput"
                       type="text"
                       class="h-14 w-full border-0 bg-transparent pl-12 pr-24 text-foreground placeholder:text-muted-foreground focus:ring-0 text-sm font-medium outline-none"
                       placeholder="Search products… try 'iphnoe' 😄"
                       role="combobox"
                       aria-expanded="false"
                       aria-controls="searchResults"
                       autocomplete="off">
 
                <div class="absolute right-4 flex items-center gap-2">
                    {{-- Bouton effacer --}}
                    <span id="searchClearBtn"
                          class="hidden cursor-pointer text-muted-foreground hover:text-foreground transition-colors p-1 rounded"
                          title="Effacer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </span>
                    <div class="text-[10px] font-bold text-muted-foreground bg-muted px-2 py-1 rounded border border-border">Ctrl K</div>
                </div>
            </div>
 
            {{-- Résultats --}}
            <div id="searchResults" class="max-h-96 scroll-py-3 overflow-y-auto custom-scrollbar" style="display:none;"></div>
 
            {{-- Pied de modal : hints clavier + compteur --}}
            <div id="searchFooter" class="hidden px-4 py-2.5 bg-muted/30 border-t border-border flex items-center justify-between text-[10px] text-muted-foreground font-mono">
                <span>↑↓ naviguer &nbsp;·&nbsp; Entrée sélectionner &nbsp;·&nbsp; Échap fermer</span>
                <span id="searchResultCount"></span>
            </div>
 
        </div>
    </div>
 
    {{-- ============================================================ --}}
    {{-- AJAX SMART SEARCH — JavaScript                               --}}
    {{-- ============================================================ --}}
    <script>
    /**
     * smartSearch — moteur de recherche AJAX avec debounce + fuzzy côté serveur
     * Endpoint : GET /search?q=  →  { results: [...], total, query }
     */
    const smartSearch = (() => {
 
        const SEARCH_URL  = '{{ route("catalog.search") }}';
        const DEBOUNCE_MS = 280;
 
        let debounceTimer  = null;
        let lastQuery      = '';
        let activeIndex    = -1;
        let inputEl        = null;
 
        // ── Utilitaires DOM ─────────────────────────────────────────
        const $ = (id) => document.getElementById(id);
 
        function showSpinner(show) {
            $('searchIcon')?.classList.toggle('hidden', show);
            $('searchSpinner')?.classList.toggle('hidden', !show);
        }
 
        function showPanel(show) {
            const panel  = $('searchResults');
            const footer = $('searchFooter');
            if (panel)  panel.style.display  = show ? 'block' : 'none';
            if (footer) footer.classList.toggle('hidden', !show);
        }
 
        function setClearBtn(show) {
            $('searchClearBtn')?.classList.toggle('hidden', !show);
        }
 
        function setCount(n, isFuzzy) {
            const el = $('searchResultCount');
            if (!el) return;
            el.textContent = n === 0 ? '' :
                isFuzzy ? `${n} résultat${n > 1 ? 's' : ''} (correction typo)`
                        : `${n} résultat${n > 1 ? 's' : ''}`;
        }
 
        // ── Échappements ─────────────────────────────────────────────
        function escHtml(str) {
            return String(str).replace(/[&<>"']/g, c =>
                ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c]));
        }
        function escRegex(str) {
            return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }
 
        // ── Surlignage des correspondances ───────────────────────────
        function highlight(text, query) {
            if (!query) return escHtml(text);
            return escHtml(text).replace(
                new RegExp(`(${escRegex(escHtml(query))})`, 'gi'),
                '<mark class="bg-primary/20 text-primary rounded px-0.5">$1</mark>'
            );
        }
 
        // ── Skeleton de chargement ───────────────────────────────────
        function renderSkeleton() {
            const html = Array(3).fill(0).map(() => `
                <div class="flex items-center gap-3 p-3 rounded-lg animate-pulse">
                    <div class="h-12 w-12 flex-shrink-0 rounded-lg bg-muted"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-3 bg-muted rounded w-3/4"></div>
                        <div class="h-2.5 bg-muted rounded w-1/4"></div>
                    </div>
                </div>`).join('');
            $('searchResults').innerHTML = `<div class="p-2 space-y-0.5">${html}</div>`;
            showPanel(true);
        }
 
        // ── Rendu des résultats ──────────────────────────────────────
        function renderResults(results, query, isFuzzy) {
            const container = $('searchResults');
            if (!container) return;
            activeIndex = -1;
 
            // Aucun résultat
            if (results.length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-10 px-4 text-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-muted-foreground/40">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-foreground">Aucun résultat pour "<span class="text-primary">${escHtml(query)}</span>"</p>
                            <p class="text-xs text-muted-foreground mt-1">Essayez d'autres mots-clés</p>
                        </div>
                        <a href="{{ route('catalog.index') }}?search=${encodeURIComponent(query)}"
                           class="mt-1 text-xs font-bold text-primary hover:underline uppercase tracking-wider">
                            Parcourir tous les produits →
                        </a>
                    </div>`;
                showPanel(true);
                setCount(0, false);
                return;
            }
 
            // Badge fuzzy (correction de typo)
            const fuzzyBadge = isFuzzy ? `
                <div class="px-3 py-1.5 bg-accent/10 border-b border-accent/20 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-accent">
                        <path fill-rule="evenodd" d="M9.315 7.584C12.195 3.883 16.695 1.5 21.75 1.5a.75.75 0 0 1 .75.75c0 5.056-2.383 9.555-6.084 12.436A6.75 6.75 0 0 1 9.75 22.5a.75.75 0 0 1-.75-.75v-4.131A15.838 15.838 0 0 1 6.382 15H2.25a.75.75 0 0 1-.75-.75 6.75 6.75 0 0 1 4.815-6.465l2.149-.719c.141-.047.288-.06.433-.038Zm-3.12 9.184 1.9-1.9a15.856 15.856 0 0 1-1.9 1.9Z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-[10px] font-bold text-accent-foreground uppercase tracking-wider">Résultats similaires (correction de faute)</span>
                </div>` : '';
 
            // Cartes produit
            const items = results.map((r, i) => `
                <a href="${escHtml(r.url)}"
                   class="search-result-item group flex items-center gap-3 p-3 rounded-lg hover:bg-accent transition-colors cursor-pointer"
                   data-index="${i}"
                   role="option">
                    <div class="h-12 w-12 flex-shrink-0 rounded-lg overflow-hidden bg-muted border border-border">
                        <img src="${escHtml(r.image_url)}"
                             alt="${escHtml(r.title)}"
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-200"
                             onerror="this.src='https://picsum.photos/seed/${r.id}/100/100'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-foreground truncate">${highlight(r.title, query)}</p>
                        <p class="text-xs text-primary font-bold mt-0.5">$${escHtml(r.price)}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                         class="w-4 h-4 text-muted-foreground opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>`).join('');
 
            // Lien "Voir tous les résultats"
            const viewAll = `
                <div class="p-2 pt-1 border-t border-border">
                    <a href="{{ route('catalog.index') }}?search=${encodeURIComponent(query)}"
                       class="flex items-center justify-center gap-2 w-full p-2.5 rounded-lg text-xs font-bold text-primary hover:bg-primary/5 transition-colors uppercase tracking-wider">
                        Voir tous les résultats pour "${escHtml(query)}"
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>`;
 
            container.innerHTML = fuzzyBadge
                + `<div class="p-2 space-y-0.5" role="listbox">${items}</div>`
                + viewAll;
 
            showPanel(true);
            setCount(results.length, isFuzzy);
        }
 
        // ── Navigation clavier ────────────────────────────────────────
        function updateActive(newIndex) {
            const items = document.querySelectorAll('.search-result-item');
            if (!items.length) return;
            items.forEach(el => el.classList.remove('bg-accent'));
            activeIndex = Math.max(-1, Math.min(newIndex, items.length - 1));
            if (activeIndex >= 0) {
                items[activeIndex].classList.add('bg-accent');
                items[activeIndex].scrollIntoView({ block: 'nearest' });
            }
        }
 
        function onKeyDown(e) {
            if (e.key === 'ArrowDown') { e.preventDefault(); updateActive(activeIndex + 1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); updateActive(activeIndex - 1); }
            else if (e.key === 'Enter' && activeIndex >= 0) {
                document.querySelectorAll('.search-result-item')[activeIndex]?.click();
            }
        }
 
        // ── Requête AJAX principale ───────────────────────────────────
        async function doSearch(query) {
            if (query === lastQuery) return;
            lastQuery = query;
 
            if (!query) { showPanel(false); setClearBtn(false); return; }
 
            setClearBtn(true);
            renderSkeleton();
            showSpinner(true);
 
            try {
                const res = await fetch(`${SEARCH_URL}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
 
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
 
                const data    = await res.json();
                const results = data.results ?? [];
 
                // Détecter si c'est un résultat fuzzy (aucun titre ne contient le terme exact)
                const isFuzzy = results.length > 0 &&
                    !results.some(r => r.title.toLowerCase().includes(query.toLowerCase()));
 
                renderResults(results, query, isFuzzy);
 
            } catch (err) {
                const container = $('searchResults');
                if (container) {
                    container.innerHTML = `
                        <div class="flex items-center gap-3 p-5 text-sm text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-warning flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <span>Erreur de recherche. Veuillez réessayer.</span>
                        </div>`;
                    showPanel(true);
                }
            } finally {
                showSpinner(false);
            }
        }
 
        // ── Gestionnaire input avec debounce ──────────────────────────
        function onInput(e) {
            const query = e.target.value.trim();
            setClearBtn(query.length > 0);
            clearTimeout(debounceTimer);
            if (!query) { lastQuery = ''; showPanel(false); return; }
            debounceTimer = setTimeout(() => doSearch(query), DEBOUNCE_MS);
        }
 
        // ── API publique ──────────────────────────────────────────────
        function init(input) {
            inputEl   = input;
            lastQuery = '';
            input.removeEventListener('input', onInput);
            input.removeEventListener('keydown', onKeyDown);
            input.addEventListener('input', onInput);
            input.addEventListener('keydown', onKeyDown);
            if (input.value.trim()) onInput({ target: input });
        }
 
        function close() {
            showPanel(false);
            showSpinner(false);
            setClearBtn(false);
            lastQuery   = '';
            activeIndex = -1;
        }
 
        // Bouton ✕ — branché une fois au chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
            $('searchClearBtn')?.addEventListener('click', () => {
                const input = $('liveSearch');
                if (input) { input.value = ''; input.focus(); }
                close();
            });
        });
 
        return { init, close };
    })();
    </script>
 
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
                            <button type="submit" class="text-xs font-black uppercase tracking-[0.2em] text-warning hover:opacity-80 transition-colors flex items-center gap-2">
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
