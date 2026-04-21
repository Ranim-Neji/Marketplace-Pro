<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function show(User $user)
    {
        if (!$user->isVendor()) {
            abort(404);
        }

        $products = $user->products()->active()->paginate(12);
        $reviews = $user->vendorReviews()->with('user')->latest()->paginate(10);
        $userReview = Auth::check() 
            ? Review::where('user_id', Auth::id())->where('vendor_id', $user->id)->whereNull('product_id')->first()
            : null;

        return view('pages.vendors.show', compact('user', 'products', 'reviews', 'userReview'));
    }
}
