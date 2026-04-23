@extends('layouts.app')
@section('title', $user->shop_name ?: $user->name)

@section('content')
<div class="container-layout py-16">
    {{-- Vendor Header --}}
    <div class="bg-card rounded-[3rem] border border-border p-8 lg:p-16 shadow-premium mb-16 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-12 opacity-5 hidden lg:block">
            <i class="fa-solid fa-shop text-[12rem]"></i>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-12">
            <div class="relative">
                <img src="{{ $user->avatar_url }}" class="h-40 w-40 rounded-full border-4 border-background shadow-2xl object-cover">
                @if($user->average_vendor_rating > 0)
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-primary text-primary-foreground px-4 py-1.5 rounded-full text-xs font-black shadow-lg flex items-center gap-2 whitespace-nowrap">
                        <i class="fa-solid fa-star text-[10px]"></i>
                        {{ number_format($user->average_vendor_rating, 1) }}
                    </div>
                @endif
            </div>

            <div class="text-center md:text-left flex-1">
                <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                    <h1 class="text-4xl lg:text-5xl font-black text-foreground tracking-tighter italic uppercase">
                        {{ $user->shop_name ?: $user->name }}
                    </h1>
                    <span class="inline-flex px-3 py-1 rounded-lg bg-primary/10 text-primary text-[10px] font-black uppercase tracking-[0.2em] border border-primary/20 w-fit self-center">
                        Verified Seller
                    </span>
                </div>
                
                <p class="text-muted-foreground text-sm max-w-2xl leading-relaxed mb-8">
                    {{ $user->shop_description ?: 'Welcome to my store! I provide high-quality products and exceptional service to our valued customers.' }}
                </p>

                <div class="flex flex-wrap justify-center md:justify-start gap-8 text-[11px] font-black uppercase tracking-[0.2em] text-muted-foreground font-mono">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-box text-primary"></i>
                        <span class="text-foreground">{{ $products->total() }} Products</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-calendar text-primary"></i>
                        <span>Since {{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-comment text-primary"></i>
                        <span class="text-foreground">{{ $reviews->total() }} Reviews</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4 w-full md:w-auto">
                <form method="POST" action="{{ route('chat.start', $user) }}">
                    @csrf
                    <button type="submit" class="w-full btn-primary py-4 px-10 text-[11px] font-black uppercase tracking-[0.3em] italic">
                        Message Seller
                    </button>
                </form>
                <button onclick="document.getElementById('reviews-section').scrollIntoView({behavior: 'smooth'})" class="w-full px-10 py-4 rounded-2xl border border-border text-[11px] font-black uppercase tracking-[0.3em] text-muted-foreground hover:text-foreground hover:border-primary transition-all">
                    View Reviews
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        {{-- Products --}}
        <div class="lg:col-span-8">
            <div class="flex items-baseline gap-6 mb-12 border-b border-border pb-8">
                <h2 class="text-2xl font-black text-foreground tracking-tighter italic uppercase">Shop Inventory</h2>
                <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest italic">Browsing all listed items</div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </div>

        {{-- Reviews --}}
        <div class="lg:col-span-4" id="reviews-section">
            <div class="flex items-baseline gap-6 mb-12 border-b border-border pb-8">
                <h2 class="text-2xl font-black text-foreground tracking-tighter italic uppercase">Seller Feedback</h2>
            </div>

            @auth
                @if(!$userReview && auth()->id() !== $user->id)
                    <div class="mb-12 p-8 rounded-[2rem] bg-muted/50 border border-border">
                        <h3 class="text-xs font-black text-foreground uppercase tracking-widest mb-6">Leave a Review</h3>
                        <form method="POST" action="{{ route('reviews.store') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $user->id }}">
                            
                            <div>
                                <div class="flex gap-2 mb-2" id="starRating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star text-xl text-border cursor-pointer star-icon transition-colors" data-value="{{ $i }}"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="" required>
                            </div>

                            <textarea name="comment" class="w-full p-4 rounded-xl bg-card border border-border text-sm focus:ring-2 focus:ring-primary/20 outline-none h-24" placeholder="Share your experience with this seller..."></textarea>
                            
                            <button type="submit" class="w-full btn-primary py-3 text-[10px] font-black uppercase tracking-[0.2em] italic">
                                Submit Feedback
                            </button>
                        </form>
                    </div>
                @endif
            @endauth

            <div class="space-y-8">
                @forelse($reviews as $review)
                    <div class="p-8 rounded-[2rem] bg-card border border-border shadow-sm group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $review->user->avatar_url }}" class="h-8 w-8 rounded-full border border-border">
                                <div class="text-[10px] font-black text-foreground uppercase tracking-wider">{{ $review->user->name }}</div>
                            </div>
                            <div class="flex text-[8px] text-accent">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i > $review->rating ? 'opacity-30' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-muted-foreground leading-relaxed italic">"{{ $review->comment }}"</p>
                        <div class="mt-4 text-[8px] font-bold text-muted-foreground uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="p-12 text-center rounded-[2rem] border-2 border-dashed border-border">
                        <p class="text-xs text-muted-foreground italic font-medium">No seller reviews yet.</p>
                    </div>
                @endforelse

                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const stars = document.querySelectorAll('.star-icon');
const ratingInput = document.getElementById('ratingInput');

stars.forEach(star => {
    star.addEventListener('mouseover', function() {
        const val = this.dataset.value;
        stars.forEach((s, i) => {
            if (i < val) {
                s.classList.replace('text-border', 'text-accent');
            } else {
                s.classList.replace('text-accent', 'text-border');
            }
        });
    });

    star.addEventListener('click', function() {
        ratingInput.value = this.dataset.value;
    });
});

document.getElementById('starRating')?.addEventListener('mouseleave', function() {
    const selected = parseInt(ratingInput.value) || 0;
    stars.forEach((s, i) => {
        if (i < selected) {
            s.classList.replace('text-border', 'text-accent');
        } else {
            s.classList.replace('text-accent', 'text-border');
        }
    });
});
</script>
@endpush
@endsection
