@props(['testimonials' => []])

<div x-data="{ 
    currentIndex: 0, 
    total: {{ count($testimonials) }},
    autoplayInterval: 5000,
    timer: null,
    
    init() {
        this.startAutoplay();
    },
    
    startAutoplay() {
        this.stopAutoplay();
        this.timer = setInterval(() => {
            this.next();
        }, this.autoplayInterval);
    },
    
    stopAutoplay() {
        if (this.timer) clearInterval(this.timer);
    },
    
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.total;
    },
    
    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.total) % this.total;
    },
    
    goTo(index) {
        this.currentIndex = index;
        this.startAutoplay(); // Reset timer on manual navigation
    }
}" 
class="relative max-w-4xl mx-auto px-4 py-12 group"
@mouseenter="stopAutoplay()"
@mouseleave="startAutoplay()">

    {{-- Carousel Stage --}}
    <div class="relative h-[400px] md:h-[300px] flex items-center justify-center overflow-hidden">
        @foreach($testimonials as $index => $testimonial)
            <div x-show="currentIndex === {{ $index }}"
                 x-transition:enter="transition ease-out duration-700 transform"
                 x-transition:enter-start="opacity-0 translate-x-12 scale-95"
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-500 transform absolute"
                 x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                 x-transition:leave-end="opacity-0 -translate-x-12 scale-95"
                 class="w-full text-center"
                 style="display: none;">
                
                <div class="space-y-8">
                    {{-- Avatar Hub --}}
                    <div class="relative inline-block">
                        <div class="absolute -inset-4 bg-indigo-500/10 rounded-full blur-2xl animate-pulse"></div>
                        <img src="{{ $testimonial['avatar'] }}" 
                             alt="{{ $testimonial['name'] }}"
                             class="relative h-24 w-24 rounded-full border-4 border-white dark:border-slate-800 shadow-2xl object-cover mx-auto transform transition-transform group-hover:scale-110 duration-700">
                        <div class="absolute -bottom-2 -right-2 h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class="fa-solid fa-quote-right text-sm"></i>
                        </div>
                    </div>

                    {{-- Text Node --}}
                    <div class="max-w-2xl mx-auto px-6">
                        <p class="text-xl md:text-2xl font-black text-slate-800 dark:text-slate-100 uppercase tracking-tighter leading-tight italic mb-8">
                            "{{ $testimonial['description'] }}"
                        </p>
                        
                        <div class="space-y-1">
                            <h4 class="text-sm font-black uppercase tracking-[0.2em] text-indigo-600">{{ $testimonial['name'] }}</h4>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 italic">{{ $testimonial['role'] ?? 'Verified Protocol Agent' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Navigation Array --}}
    <div class="absolute top-1/2 -translate-y-1/2 left-0 right-0 flex justify-between pointer-events-none px-2 sm:px-0">
        <button @click="prev()" 
                class="pointer-events-auto h-12 w-12 rounded-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-100 dark:border-slate-800 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:scale-110 active:scale-95 transition-all shadow-xl -translate-x-2 md:-translate-x-12 opacity-0 group-hover:opacity-100 group-hover:translate-x-0">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button @click="next()" 
                class="pointer-events-auto h-12 w-12 rounded-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-100 dark:border-slate-800 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:scale-110 active:scale-95 transition-all shadow-xl translate-x-2 md:translate-x-12 opacity-0 group-hover:opacity-100 group-hover:translate-x-0">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    {{-- Indicators --}}
    <div class="mt-12 flex justify-center gap-3">
        @foreach($testimonials as $index => $testimonial)
            <button @click="goTo({{ $index }})"
                    class="h-1.5 transition-all duration-500 rounded-full"
                    :class="currentIndex === {{ $index }} ? 'w-8 bg-indigo-600' : 'w-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700'"></button>
        @endforeach
    </div>
</div>
