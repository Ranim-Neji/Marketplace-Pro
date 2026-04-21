<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, User, Review};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalVendors = User::role('vendor')->count();
        $totalBuyers = User::role('buyer')->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['validated', 'shipped', 'delivered', 'completed'])->sum('total');
        
        $bestsellers = Product::where('is_bestseller', true)->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        $orderStats = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Map colors for the view
        $colors = [
            'pending' => 'amber', 'processing' => 'sky', 'validated' => 'emerald',
            'shipped' => 'indigo', 'delivered' => 'emerald', 'cancelled' => 'rose', 'completed' => 'slate'
        ];
        
        foreach ($orderStats as $stat) {
            $stat->color = $colors[$stat->status] ?? 'indigo';
        }

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $salesData = Order::whereIn('status', ['validated', 'shipped', 'delivered', 'completed'])
            ->selectRaw('DATE_FORMAT(created_at, "%M") as month, SUM(total) as revenue')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy(DB::raw('MIN(created_at)'))
            ->get();

        $revenueLabels = $salesData->pluck('month');
        $revenueValues = $salesData->pluck('revenue');

        return view('pages.admin.dashboard', compact(
            'totalUsers', 'totalVendors', 'totalBuyers', 'totalProducts', 'totalOrders', 'totalRevenue', 
            'recentOrders', 'orderStats', 'revenueLabels', 'revenueValues', 'bestsellers', 'recentUsers'
        ));
    }
}
