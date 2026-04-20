@extends('layouts.app')

@section('title', 'Category Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">{{ $category->name }}</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">Category Info</div>
            <div class="card-body">
                <p><strong>Slug:</strong> {{ $category->slug }}</p>
                <p><strong>Status:</strong> {{ $category->is_active ? 'Active' : 'Inactive' }}</p>
                <p><strong>Products:</strong> {{ $category->products->count() }}</p>
                <p class="mb-0"><strong>Description:</strong> {{ $category->description ?: 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">Products in Category</div>
            <div class="card-body">
                @forelse($category->products->take(10) as $product)
                    <a href="{{ route('admin.products.show', $product) }}" class="d-block text-decoration-none mb-2">{{ $product->title }}</a>
                @empty
                    <p class="text-muted mb-0">No products linked to this category.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
