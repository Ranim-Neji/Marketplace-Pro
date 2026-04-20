<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()
            ->with(['user:id,name,email', 'categories'])
            ->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $products = $query->paginate(20)->withQueryString();

        return view('pages.admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $vendors = User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.admin.products.create', compact('vendors', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,draft'],
            'is_featured' => ['nullable', 'boolean'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ]);

        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['image'] = 'products/default.jpg';
        $data['short_description'] = Str::limit($data['description'], 120);
        $product = Product::create($data);
        $product->categories()->sync($data['categories']);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $product->load(['user', 'categories', 'reviews.user', 'images']);

        return view('pages.admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $vendors = User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.admin.products.edit', compact('product', 'vendors', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive,draft'],
            'is_featured' => ['nullable', 'boolean'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ]);

        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['short_description'] = Str::limit($data['description'], 120);

        $product->update($data);
        $product->categories()->sync($data['categories']);

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        return back()->with('success', $product->is_featured ? 'Product featured.' : 'Product unfeatured.');
    }
}
