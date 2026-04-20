@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">{{ $product->title }}</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <img src="{{ $product->image_url }}" alt="{{ $product->title }}" class="card-img-top" style="max-height: 360px; object-fit: cover;">
            <div class="card-body">
                <p>{{ $product->description }}</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->categories as $category)
                        <span class="badge text-bg-light border">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white fw-semibold">Meta</div>
            <div class="card-body">
                <p><strong>Seller:</strong> {{ $product->user->name }} ({{ $product->user->email }})</p>
                <p><strong>Status:</strong> {{ ucfirst($product->status) }}</p>
                <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                <p><strong>Sale Price:</strong> {{ $product->sale_price ? '$'.number_format($product->sale_price, 2) : '-' }}</p>
                <p><strong>Stock:</strong> {{ $product->stock }}</p>
                <p><strong>Average Rating:</strong> {{ number_format($product->average_rating, 1) }}</p>
                <p class="mb-0"><strong>Views:</strong> {{ $product->views_count }}</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">Recent Reviews</div>
            <div class="card-body">
                @forelse($product->reviews->take(5) as $review)
                    <div class="border-bottom pb-2 mb-2">
                        <div class="fw-semibold small">{{ $review->user->name }}</div>
                        <div class="small text-warning">{{ str_repeat('★', (int) $review->rating) }}</div>
                        <div class="small">{{ \Illuminate\Support\Str::limit($review->comment, 90) }}</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No reviews yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
