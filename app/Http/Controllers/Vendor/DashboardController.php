<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $totalProducts = $user->products()->count();
        $totalServices = $user->services()->count();
        
        // Count total orders that contain this vendor's products
        $totalSalesCount = OrderItem::whereIn('product_id', $user->products()->pluck('id'))->count();
        
        // Sum total revenue for this vendor
        $totalRevenue = OrderItem::whereIn('product_id', $user->products()->pluck('id'))
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['validated', 'shipped', 'delivered', 'completed']);
            })
            ->sum(DB::raw('price * quantity'));

        $recentProducts = $user->products()
            ->with('categories')
            ->latest()
            ->take(5)
            ->get();

        $recentSales = OrderItem::with(['order.user', 'product'])
            ->whereIn('product_id', $user->products()->pluck('id'))
            ->latest()
            ->take(5)
            ->get();

        return view('pages.vendor.dashboard', compact(
            'totalProducts', 'totalServices', 'totalSalesCount', 'totalRevenue', 'recentProducts', 'recentSales'
        ));
    }
}
