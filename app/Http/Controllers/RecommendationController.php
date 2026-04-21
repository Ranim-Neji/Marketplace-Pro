<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserBehavior;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    public function __construct()
    {
        // middleware works now because Controller inheritance is correct
        $this->middleware(['auth', 'verified', 'active'])->except('getRecommendations');
    }

    public function index()
    {
        $recommendedProducts = $this->getRecommendations(Auth::id());

        $trendingProducts = Product::active()
            ->orderByDesc('views_count')
            ->take(4)
            ->get();

        return view('pages.recommendations.index', compact(
            'recommendedProducts',
            'trendingProducts'
        ));
    }

    /**
     * Recommendation Engine:
     * - Content-based filtering (categories)
     * - Collaborative filtering (similar users)
     * - Popular fallback
     */
    public function getRecommendations(?int $userId, int $limit = 8): \Illuminate\Support\Collection
    {
        // Fallback for guests or if no history
        if (!$userId) {
            return Product::active()
                ->orderByDesc('views_count')
                ->take($limit)
                ->get();
        }

        // 1. Get preferred categories from behavior scores
        $preferredCategoryIds = DB::table('user_behaviors')
            ->join('product_category', 'user_behaviors.product_id', '=', 'product_category.product_id')
            ->where('user_behaviors.user_id', $userId)
            ->select('product_category.category_id', DB::raw('SUM(user_behaviors.score) as weight'))
            ->groupBy('product_category.category_id')
            ->orderByDesc('weight')
            ->take(3)
            ->pluck('category_id');

        // 2. Products already seen
        $interactedProductIds = UserBehavior::where('user_id', $userId)
            ->pluck('product_id');

        // 3. Content-based recommendations
        $contentBased = Product::active()
            ->whereHas('categories', function ($q) use ($preferredCategoryIds) {
                $q->whereIn('categories.id', $preferredCategoryIds);
            })
            ->whereNotIn('id', $interactedProductIds)
            ->orderByDesc('average_rating')
            ->take($limit)
            ->get();

        $results = $contentBased;

        // 4. Collaborative filtering if not enough results
        if ($results->count() < $limit) {
            $similarUserIds = DB::table('user_behaviors as ub1')
                ->join('user_behaviors as ub2', 'ub1.product_id', '=', 'ub2.product_id')
                ->where('ub1.user_id', $userId)
                ->where('ub2.user_id', '!=', $userId)
                ->select('ub2.user_id', DB::raw('COUNT(*) as matches'))
                ->groupBy('ub2.user_id')
                ->orderByDesc('matches')
                ->take(5)
                ->pluck('ub2.user_id');

            $collaborative = Product::active()
                ->whereHas('behaviors', function ($q) use ($similarUserIds) {
                    $q->whereIn('user_id', $similarUserIds)
                      ->where('action', 'purchase');
                })
                ->whereNotIn('id', $interactedProductIds)
                ->whereNotIn('id', $results->pluck('id'))
                ->take($limit - $results->count())
                ->get();

            $results = $results->merge($collaborative);
        }

        // 5. Fallback: trending products if still not enough
        if ($results->count() < $limit) {
            $trending = Product::active()
                ->whereNotIn('id', $interactedProductIds)
                ->whereNotIn('id', $results->pluck('id'))
                ->orderByDesc('views_count')
                ->take($limit - $results->count())
                ->get();

            $results = $results->merge($trending);
        }

        // Final fallback if everything else fails (e.g. user saw everything trending)
        if ($results->isEmpty()) {
            return Product::active()->latest()->take($limit)->get();
        }

        return $results;
    }
}