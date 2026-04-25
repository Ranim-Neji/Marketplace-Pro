@extends('layouts.app')

@section('title', 'Browse Services | MarketPlace Pro')

@section('content')
<div class="container-layout py-8 lg:py-12" x-data="serviceFilter()">
    {{-- Header --}}
    <div class="mb-8 border-b border-border pb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-foreground tracking-tight">Professional Services</h1>
            <p class="text-sm text-muted-foreground" id="catalog-count">Showing {{ $services->firstItem() ?? 0 }}-{{ $services->lastItem() ?? 0 }} of {{ $services->total() }} results</p>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="text-xs font-semibold text-muted-foreground uppercase tracking-widest font-mono">Sort by</span>
            <select name="sort" form="filterForm" @change="fetchResults()" class="bg-card border border-border text-foreground text-sm font-medium rounded-lg px-3 py-2 focus:ring-1 focus:ring-ring outline-none">
                <option value="newest" @selected(request('sort') === 'newest')>Newest Arrivals</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
            </select>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-10">
        {{-- Filters Sidebar --}}
        <aside class="w-full lg:w-64 shrink-0">
            <div class="sticky top-24 panel p-6 bg-sidebar border-sidebar-border">
                <h3 class="text-sm font-semibold text-sidebar-foreground mb-6 font-serif italic">Filters</h3>
                <form action="{{ route('services.index') }}" method="GET" class="space-y-6" id="filterForm" @submit.prevent="fetchResults()">
                    {{-- Search --}}
                    <div class="space-y-3">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-muted-foreground font-mono">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Service name..." class="input-base bg-card" @input.debounce.500ms="fetchResults()">
                    </div>

                    {{-- Categories --}}
                    <div class="space-y-3">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-muted-foreground font-mono">Category</label>
                        <select name="category_id" class="input-base bg-card" @change="fetchResults()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price --}}
                    <div class="space-y-3">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-muted-foreground font-mono">Price Range</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="input-base bg-card text-center" @input.debounce.500ms="fetchResults()">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="input-base bg-card text-center" @input.debounce.500ms="fetchResults()">
                        </div>
                    </div>

                    {{-- Toggles --}}
                    <div class="space-y-3 pt-6 border-t border-sidebar-border">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="available" value="1" @checked(request()->boolean('available')) @change="fetchResults()" class="h-4 w-4 rounded border-border text-sidebar-primary focus:ring-sidebar-ring bg-card transition-colors">
                            <span class="text-sm font-medium text-sidebar-foreground group-hover:text-primary transition-colors">Available Only</span>
                        </label>
                    </div>

                    <div class="pt-6 flex flex-col gap-3 border-t border-sidebar-border">
                        <x-button type="button" @click="resetFilters()" class="w-full" variant="secondary">Reset Filters</x-button>
                    </div>
                </form>

                {{-- CTA for Experts --}}
                <div class="mt-10 p-6 bg-slate-900 rounded-2xl text-white overflow-hidden relative group">
                    <div class="relative z-10">
                        <h4 class="text-sm font-black uppercase tracking-tighter mb-2 italic">Are you an expert?</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed mb-4">Start selling your professional services today.</p>
                        <a href="{{ route('profile.become-vendor') }}" class="block text-center py-2 bg-white text-slate-900 text-[9px] font-black uppercase tracking-[0.2em] rounded-lg hover:bg-primary hover:text-white transition-all">Become a Vendor</a>
                    </div>
                    <div class="absolute -right-4 -bottom-4 h-24 w-24 bg-primary/20 rounded-full blur-2xl group-hover:bg-primary/40 transition-all"></div>
                </div>
            </div>
        </aside>

        {{-- Grid Area --}}
        <div class="flex-1 relative">
            <div x-show="loading" class="absolute inset-0 bg-background/50 backdrop-blur-sm z-10 flex items-start justify-center pt-20 transition-opacity" style="display: none;">
                <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
            </div>

            <div id="catalog-results" @click="interceptPagination($event)">
                @if($services->isEmpty())
                    <div class="py-20 flex flex-col items-center justify-center text-center border border-dashed border-border rounded-xl bg-accent/30">
                        <div class="h-12 w-12 bg-card rounded-lg flex items-center justify-center mb-4 text-muted-foreground shadow-sm">
                            <i class="fa-solid fa-search text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-foreground mb-1">No services found</h3>
                        <p class="text-sm text-muted-foreground max-w-sm">Try adjusting your filters or search query to find what you're looking for.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($services as $service)
                            <x-service-card :service="$service" />
                        @endforeach
                    </div>

                    <div class="mt-12 pt-8 border-t border-border flex justify-end">
                        {{ $services->links('components.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function serviceFilter() {
    return {
        loading: false,
        interceptPagination(e) {
            const a = e.target.closest('a');
            if (a && a.href && a.href.includes('?page=')) {
                e.preventDefault();
                this.fetchResults(a.href);
                window.scrollTo({ top: document.getElementById('catalog-results').offsetTop - 100, behavior: 'smooth' });
            }
        },
        fetchResults(customUrl = null) {
            this.loading = true;
            let url = customUrl;
            
            if (!url) {
                const form = document.getElementById('filterForm');
                const formData = new FormData(form);
                const searchParams = new URLSearchParams();
                
                for (const [key, value] of formData) {
                    if (value) searchParams.append(key, value);
                }
                
                const sortSelect = document.querySelector('select[name="sort"]');
                if (sortSelect && sortSelect.value) {
                    searchParams.append('sort', sortSelect.value);
                }
                
                url = `${window.location.pathname}?${searchParams.toString()}`;
            }
            
            window.history.pushState({}, '', url);
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    document.getElementById('catalog-results').innerHTML = doc.getElementById('catalog-results').innerHTML;
                    document.getElementById('catalog-count').innerHTML = doc.getElementById('catalog-count').innerHTML;
                    
                    this.loading = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        },
        resetFilters() {
            const form = document.getElementById('filterForm');
            form.reset();
            form.querySelectorAll('input[type="text"], input[type="number"]').forEach(el => el.value = '');
            form.querySelectorAll('input[type="checkbox"]').forEach(el => el.checked = false);
            form.querySelectorAll('select').forEach(el => el.selectedIndex = 0);
            
            const sortSelect = document.querySelector('select[name="sort"]');
            if(sortSelect) sortSelect.selectedIndex = 0;
            
            this.fetchResults();
        }
    }
}
</script>
@endsection
