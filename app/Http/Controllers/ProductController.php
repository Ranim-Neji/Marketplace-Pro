<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\UserBehavior;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active'])->except(['show']);
    }

    public function index()
    {
        $products = Auth::user()
            ->products()
            ->with('categories')
            ->latest()
            ->paginate(12);

        return view('pages.products.index', compact('products'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $data['is_featured'] = $request->boolean('is_featured');

            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'), 'products');
            }

            /** @var Product $product */
            $product = Product::create($data);
            $product->categories()->sync($data['categories']);

            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $index => $imageFile) {
                    $product->images()->create([
                        'image_path' => $this->uploadImage($imageFile, 'products/gallery'),
                        'sort_order' => $index,
                    ]);
                }
            }

            return $product;
        });

        return redirect()
            ->route('vendor.products.edit', $product)
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load([
            'categories',
            'images',
            'user',
            'reviews.user',
        ]);

        $product->increment('views_count');

        if (Auth::check()) {
            UserBehavior::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'action' => 'view',
                'score' => UserBehavior::SCORES['view'],
            ]);
        }

        $relatedProducts = Product::active()
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->with('categories')
            ->latest()
            ->take(4)
            ->get();

        $userReview = Auth::check()
            ? $product->allReviews()->where('user_id', Auth::id())->first()
            : null;

        return view('pages.products.show', compact('product', 'relatedProducts', 'userReview'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedCategories = $product->categories->pluck('id')->all();

        return view('pages.products.edit', compact('product', 'categories', 'selectedCategories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        DB::transaction(function () use ($request, $product): void {
            $data = $request->validated();
            $data['is_featured'] = $request->boolean('is_featured');

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                $data['image'] = $this->uploadImage($request->file('image'), 'products');
            }

            $product->update($data);
            $product->categories()->sync($data['categories']);

            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $index => $imageFile) {
                    $product->images()->create([
                        'image_path' => $this->uploadImage($imageFile, 'products/gallery'),
                        'sort_order' => $index,
                    ]);
                }
            }
        });

        return redirect()
            ->route('vendor.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()
            ->route('vendor.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function uploadImage($file, string $folder): string
    {
        $filename = uniqid('img_', true).'.'.$file->getClientOriginalExtension();
        $path = $folder.'/'.$filename;

        $image = Image::read($file);
        $image->scale(width: 1200);
        Storage::disk('public')->put($path, $image->toJpeg(85));

        return $path;
    }
}
