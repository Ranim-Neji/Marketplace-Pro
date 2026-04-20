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
        $product = Product::findOrFail($request->product_id);

        // Check if user already reviewed this product
        $existing = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        // Check if user has actually purchased the product
        $hasPurchased = Auth::user()->orders()
            ->whereIn('status', ['validated', 'delivered', 'completed'])
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        Review::create([
            'product_id'  => $product->id,
            'user_id'     => Auth::id(),
            'rating'      => $request->rating,
            'title'       => $request->title,
            'comment'     => $request->comment,
            'is_approved' => $hasPurchased, // auto-approve if verified buyer
        ]);

        // Update product average rating
        $product->updateAverageRating();

        return back()->with('success', $hasPurchased
            ? 'Review published successfully!'
            : 'Review submitted and pending approval.');
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
