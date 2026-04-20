<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->active()
            ->with(['categories', 'user:id,name,shop_name']);

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        if ($category = $request->input('category')) {
            $query->whereHas('categories', fn($q) => $q->where('slug', $category));
        }

        if ($request->filled('vendor_id')) {
            $query->where('user_id', (int) $request->input('vendor_id'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        match ($request->input('sort', 'newest')) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc('average_rating'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->latest(),
        };

        $perPage = max(1, min((int) $request->input('per_page', 15), 50));
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $products->items(),
            'meta'    => [
                'total'        => $products->total(),
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
            ]
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['categories', 'reviews.user:id,name', 'images', 'user:id,name,shop_name']);

        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }
}
