<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserBehavior;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active']);
    }

    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product.categories')
            ->latest()
            ->paginate(12);

        return view('pages.wishlist.index', compact('wishlist'));
    }

    public function toggle(Product $product)
    {
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Removed from wishlist.';
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
            ]);

            // Track behavior for recommendations
            UserBehavior::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'action'     => 'wishlist',
                'score'      => UserBehavior::SCORES['wishlist'],
            ]);

            $message = 'Added to wishlist!';
            $inWishlist = true;
        }

        if (request()->ajax()) {
            return response()->json([
                'success'    => true,
                'in_wishlist' => $inWishlist,
                'message'    => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
