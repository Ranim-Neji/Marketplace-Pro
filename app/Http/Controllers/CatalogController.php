<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->active()
            ->with(['categories', 'user']);

        if ($search = trim((string) $request->string('search'))) {
            $query->search($search);
        }

        if ($categorySlug = $request->input('category')) {
            $query->whereHas('categories', fn($q) =>
                $q->where('slug', $categorySlug)
            );
        }

        if ($vendorId = $request->integer('vendor_id')) {
            $query->where('user_id', $vendorId);
        }

        if ($minPrice = $request->input('min_price')) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($minRating = $request->input('min_rating')) {
            $query->where('average_rating', '>=', (float) $minRating);
        }

        match ($request->input('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('views_count', 'desc'),
            'rating'     => $query->orderBy('average_rating', 'desc'),
            'name_asc'   => $query->orderBy('title', 'asc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();
        $vendors = User::query()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('is_vendor', true)
                    ->orWhereHas('roles', fn($roleQuery) => $roleQuery->where('name', 'vendor'));
            })
            ->select('id', 'name', 'shop_name')
            ->orderBy('name')
            ->get();

        if (request()->routeIs('home')) {
            $bestsellers = Product::active()
                ->where('is_bestseller', true)
                ->orderBy('average_rating', 'desc')
                ->take(10)
                ->get();

            $featuredProducts = Product::active()
                ->where('is_featured', true)
                ->with(['categories', 'user'])
                ->latest()
                ->take(8)
                ->get();

            $recommendedProducts = collect();
            if (auth()->check()) {
                // Call recommendation logic (simplified for home)
                $recommendedProducts = (new RecommendationController())->getRecommendations(auth()->id(), 4);
            }

            return view('pages.home', compact('bestsellers', 'featuredProducts', 'recommendedProducts'));
        }

        return view('pages.catalog.index', compact('products', 'categories', 'vendors'));
    }

    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2|max:100']);

        $products = Product::active()
            ->search($request->q)
            ->with('categories')
            ->take(8)
            ->get(['id', 'title', 'slug', 'price', 'image']);

        return response()->json($products->map(fn($p) => [
            'id'        => $p->id,
            'title'     => $p->title,
            'price'     => number_format($p->effective_price, 2),
            'url'       => route('products.show', $p),
            'image_url' => $p->image_url,
        ]));
    }
}
