@extends('layouts.app')
@section('title', $product->title)

@section('content')
<div class="container-layout py-12 lg:py-16">
    {{-- Breadcrumb --}}
    <nav class="mb-10 flex items-center gap-3 text-xs font-medium text-muted-foreground">
        <a href="{{ route('home') }}" class="hover:text-foreground transition-colors">Home</a>
        <i class="fa-solid fa-chevron-right text-[8px] opacity-50"></i>
        <a href="{{ route('catalog.index') }}" class="hover:text-foreground transition-colors">Catalogue</a>
        <i class="fa-solid fa-chevron-right text-[8px] opacity-50"></i>
        @foreach($product->categories->take(1) as $cat)
            <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="hover:text-foreground transition-colors">{{ $cat->name }}</a>
            <i class="fa-solid fa-chevron-right text-[8px] opacity-50"></i>
        @endforeach
        <span class="text-foreground truncate max-w-[200px]">{{ $product->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
        {{-- Visuals --}}
        <div class="lg:col-span-6">
            <div class="sticky top-24 space-y-6">
                <div class="relative aspect-square rounded-[2rem] overflow-hidden bg-muted border border-border shadow-premium group">
                    <img id="mainImage"
                         src="{{ $product->image_url }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                         alt="{{ $product->title }}">
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count() > 0)
                    <div class="flex gap-4 overflow-x-auto pb-2 custom-scrollbar">
                        <div class="h-20 w-20 shrink-0 rounded-xl overflow-hidden border-2 border-primary cursor-pointer thumbnail-box bg-muted transition-colors"
                             onclick="switchImage(this, '{{ $product->image_url }}')">
                            <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                        </div>
                        @foreach($product->images as $img)
                            <div class="h-20 w-20 shrink-0 rounded-xl overflow-hidden border-2 border-transparent hover:border-primary/50 transition-all cursor-pointer thumbnail-box bg-muted"
                                 onclick="switchImage(this, '{{ asset('storage/' . $img->image_path) }}')">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Details --}}
        <div class="lg:col-span-6">
            <div class="space-y-10">
                {{-- Header --}}
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @foreach($product->categories as $cat)
                            <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}"
                               class="px-3 py-1 rounded-full bg-muted border border-border text-[10px] font-semibold uppercase tracking-wider text-muted-foreground hover:text-primary hover:border-primary transition-all">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>

                    <h1 class="text-3xl md:text-5xl font-semibold text-foreground tracking-tight mb-4 font-serif italic">
                        {{ $product->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 text-[11px] font-semibold uppercase tracking-wider text-muted-foreground font-mono">
                        <div class="flex items-center gap-2">
                            <div class="flex text-[var(--chart-4)]">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star{{ $i <= round($product->average_rating) ? '' : '-half-stroke' }} {{ $i > ceil($product->average_rating) ? 'opacity-30' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="text-foreground">{{ number_format($product->average_rating, 1) }}</span>
                            <span>({{ $product->reviews->count() }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-eye opacity-50"></i>
                            <span>{{ number_format($product->views_count) }} Views</span>
                        </div>
                        @if($product->sku)
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-barcode opacity-50"></i>
                                <span>SKU: {{ $product->sku }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pricing & Actions --}}
                <div class="bg-card rounded-2xl border border-border p-8 shadow-premium relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6">
                        @if($product->isInStock())
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[var(--chart-3)]/10 text-[var(--chart-3)] text-[10px] font-bold uppercase tracking-wider border border-[var(--chart-3)]/20">
                                <div class="h-1.5 w-1.5 rounded-full bg-[var(--chart-3)] animate-pulse"></div>
                                In Stock
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider border border-red-100">
                                <div class="h-1.5 w-1.5 rounded-full bg-red-500"></div>
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <div class="flex items-end gap-4 mb-8">
                        <div class="text-4xl font-bold text-foreground">
                            ${{ number_format($product->effective_price, 2) }}
                        </div>
                        @if($product->sale_price)
                            <div class="mb-1">
                                <div class="text-lg text-muted-foreground line-through decoration-2 decoration-primary/50">${{ number_format($product->price, 2) }}</div>
                            </div>
                            <div class="mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-primary bg-primary/10 uppercase tracking-wider">
                                    SAVE {{ round((1 - $product->sale_price / $product->price) * 100) }}%
                                </span>
                            </div>
                        @endif
                    </div>

                    @auth
                        @if($product->isInStock())
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="flex flex-col sm:flex-row gap-4">
                                @csrf
                                <div class="flex items-center bg-muted rounded-xl border border-border p-1">
                                    <button type="button" onclick="adjustQty(-1)" class="h-12 w-12 flex items-center justify-center text-muted-foreground hover:text-foreground transition-colors"><i class="fa-solid fa-minus text-sm"></i></button>
                                    <input type="number" name="quantity" id="qty" class="w-12 bg-transparent text-center font-semibold text-foreground outline-none"
                                           value="1" min="1" max="{{ $product->stock }}" readonly>
                                    <button type="button" onclick="adjustQty(1)" class="h-12 w-12 flex items-center justify-center text-muted-foreground hover:text-foreground transition-colors"><i class="fa-solid fa-plus text-sm"></i></button>
                                </div>
                                <button type="submit" class="flex-1 btn-primary py-3 px-8 text-sm font-semibold group">
                                    Add to Cart
                                </button>
                            </form>
                        @endif

                        <div class="mt-6 flex flex-wrap gap-3">
                            <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                                @csrf
                                @php $inWishlist = auth()->user()->wishlist->contains('product_id', $product->id); @endphp
                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-border hover:border-primary group transition-all bg-card shadow-sm">
                                    <i class="fa-solid fa-heart {{ $inWishlist ? 'text-primary' : 'text-muted-foreground group-hover:text-primary' }} transition-colors"></i>
                                    <span class="text-[11px] font-semibold text-foreground">
                                        {{ $inWishlist ? 'Saved' : 'Save' }}
                                    </span>
                                </button>
                            </form>

                            @if($product->user_id !== auth()->id())
                                <form method="POST" action="{{ route('chat.start', $product->user) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-primary/20 hover:border-primary group transition-all bg-primary/5 shadow-sm">
                                        <i class="fa-solid fa-message text-primary group-hover:scale-110 transition-transform"></i>
                                        <span class="text-[11px] font-black uppercase tracking-widest text-primary">Contact Seller</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="block text-center btn-primary py-3 text-sm font-semibold">
                            Login to Purchase
                        </a>
                    @endauth
                </div>

                {{-- Specs --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest text-muted-foreground font-mono">
                        <div class="h-px flex-1 bg-border"></div>
                        Description
                        <div class="h-px flex-1 bg-border"></div>
                    </div>

                    <div class="text-sm text-foreground leading-relaxed">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    {{-- Seller Info --}}
                    <div class="p-6 rounded-2xl bg-muted/50 border border-border flex items-center gap-5 mt-8">
                        <img src="{{ $product->user->avatar_url }}" class="h-14 w-14 rounded-full border border-border object-cover">
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-semibold text-foreground truncate">{{ $product->user->shop_name ?: $product->user->name }}</div>
                            <div class="text-[11px] text-muted-foreground mt-0.5">Member since {{ $product->user->created_at->format('Y') }}</div>
                        </div>
                        <a href="#" class="h-10 w-10 flex items-center justify-center rounded-lg bg-card border border-border text-muted-foreground hover:text-foreground transition-colors shadow-sm">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Reviews --}}
                <div class="pt-12 border-t border-border">
                    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8">
                        <div>
                            <h2 class="text-2xl font-semibold text-foreground tracking-tight font-serif italic">Reviews</h2>
                            <div class="text-sm text-muted-foreground mt-1">{{ $product->reviews->count() }} total</div>
                        </div>
                        
                        @auth
                            @if(!$userReview)
                                <button onclick="document.getElementById('reviewSection').classList.toggle('hidden')" class="btn-secondary py-2 px-6 text-sm font-semibold">
                                    Write a Review
                                </button>
                            @endif
                        @endauth
                    </div>

                    @auth
                        @if(!$userReview)
                            <div id="reviewSection" class="hidden mb-12 animate-fade-in">
                                <form method="POST" action="{{ route('reviews.store') }}" class="bg-card rounded-2xl border border-border p-8 shadow-premium">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-semibold text-foreground mb-3">Rating</label>
                                            <div class="flex gap-2" id="starRating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-solid fa-star text-2xl text-border cursor-pointer star-icon transition-colors"
                                                       data-value="{{ $i }}"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="ratingInput" value="">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-foreground mb-2">Title</label>
                                            <input type="text" name="title" class="input-base" placeholder="Brief summary...">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-foreground mb-2">Review</label>
                                            <textarea name="comment" class="input-base h-32 py-3" placeholder="What did you like or dislike?"></textarea>
                                        </div>
                                    </div>

                                    <div class="mt-8 flex justify-end">
                                        <button type="submit" class="btn-primary py-2.5 px-8 text-sm font-semibold">
                                            Submit Review
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endauth

                    <div class="space-y-8">
                        @forelse($product->reviews as $review)
                            <div class="flex gap-5 group">
                                <img src="{{ $review->user->avatar_url }}" class="h-10 w-10 rounded-full shrink-0 border border-border object-cover">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <div>
                                            <div class="text-sm font-semibold text-foreground">{{ $review->user->name }}</div>
                                            <div class="text-[11px] text-muted-foreground">{{ $review->created_at->diffForHumans() }}</div>
                                        </div>
                                        <div class="flex text-[10px] text-[var(--chart-4)]">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star {{ $i > $review->rating ? 'opacity-30' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->title)
                                        <div class="text-sm font-semibold text-foreground mt-2 mb-1">{{ $review->title }}</div>
                                    @endif
                                    @if($review->comment)
                                        <p class="text-sm text-muted-foreground leading-relaxed">{{ $review->comment }}</p>
                                    @endif
                                    
                                    @if(auth()->check() && (auth()->id() === $review->user_id || auth()->user()->isAdmin()))
                                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="mt-3">
                                            @csrf @method('DELETE')
                                            <button class="text-[11px] font-medium text-red-500 hover:text-red-600 transition-colors"
                                                    onclick="return confirm('Delete this review?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center bg-muted/30 rounded-2xl border border-dashed border-border">
                                <p class="text-sm text-muted-foreground">No reviews yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-24 pt-12 border-t border-border">
            <h4 class="text-2xl font-semibold text-foreground tracking-tight font-serif italic mb-8">You might also like</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function switchImage(thumbBox, url) {
    document.getElementById('mainImage').src = url;
    document.querySelectorAll('.thumbnail-box').forEach(t => t.classList.remove('border-primary'));
    thumbBox.classList.add('border-primary');
}

function adjustQty(delta) {
    const input = document.getElementById('qty');
    const max = parseInt(input.max);
    const newVal = Math.min(max, Math.max(1, parseInt(input.value) + delta));
    input.value = newVal;
}

const stars = document.querySelectorAll('.star-icon');
const ratingInput = document.getElementById('ratingInput');

stars.forEach(star => {
    star.addEventListener('mouseover', function() {
        const val = this.dataset.value;
        stars.forEach((s, i) => {
            if (i < val) {
                s.classList.replace('text-border', 'text-[var(--chart-4)]');
            } else {
                s.classList.replace('text-[var(--chart-4)]', 'text-border');
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
            s.classList.replace('text-border', 'text-[var(--chart-4)]');
        } else {
            s.classList.replace('text-[var(--chart-4)]', 'text-border');
        }
    });
});
</script>
@endpush
