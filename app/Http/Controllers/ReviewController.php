<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active']);
    }

    public function store(StoreReviewRequest $request)
    {
        $productId = $request->product_id;
        $vendorId = $request->vendor_id;

        if (!$productId && !$vendorId) {
            return back()->with('error', 'Invalid review target.');
        }

        // Check if user already reviewed this target
        $existing = Review::where('user_id', Auth::id())
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->when($vendorId, fn($q) => $q->where('vendor_id', $vendorId))
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this.');
        }

        $hasPurchased = false;
        if ($productId) {
            $product = Product::findOrFail($productId);
            $hasPurchased = Auth::user()->orders()
                ->whereIn('status', ['validated', 'delivered', 'completed'])
                ->whereHas('items', fn($q) => $q->where('product_id', $productId))
                ->exists();
            $vendorId = $product->user_id; // Product reviews also count towards vendor
        }

        Review::create([
            'product_id'  => $productId,
            'vendor_id'   => $vendorId,
            'user_id'     => Auth::id(),
            'rating'      => $request->rating,
            'title'       => $request->title,
            'comment'     => $request->comment,
            'is_approved' => $hasPurchased || !$productId, // auto-approve if verified buyer or pure vendor review
        ]);

        if ($productId) {
            Product::find($productId)->updateAverageRating();
        }

        return back()->with('success', 'Review submitted successfully!');
    }

    public function destroy(Review $review)
    {
        abort_if(
            $review->user_id !== Auth::id() && !Auth::user()->isAdmin(),
            403
        );

        $product = $review->product;
        $review->delete();
        $product->updateAverageRating();

        return back()->with('success', 'Review deleted.');
    }
}
