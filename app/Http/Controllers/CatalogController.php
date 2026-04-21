<?php
<<<<<<< HEAD

namespace App\Http\Controllers;

=======
 
namespace App\Http\Controllers;
 
>>>>>>> ajax-smart-search
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
<<<<<<< HEAD

=======
 
>>>>>>> ajax-smart-search
class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->active()
            ->with(['categories', 'user']);
<<<<<<< HEAD

        if ($search = trim((string) $request->string('search'))) {
            $query->search($search);
        }

=======
 
        if ($search = trim((string) $request->string('search'))) {
            $query->search($search);
        }
 
>>>>>>> ajax-smart-search
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('categories', fn($q) =>
                $q->where('slug', $categorySlug)
            );
        }
<<<<<<< HEAD

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

=======
 
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
 
>>>>>>> ajax-smart-search
        match ($request->input('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('views_count', 'desc'),
            'rating'     => $query->orderBy('average_rating', 'desc'),
            'name_asc'   => $query->orderBy('title', 'asc'),
            default      => $query->latest(),
        };
<<<<<<< HEAD

=======
 
>>>>>>> ajax-smart-search
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
<<<<<<< HEAD

=======
 
>>>>>>> ajax-smart-search
        if (request()->routeIs('home')) {
            $bestsellers = Product::active()
                ->where('is_bestseller', true)
                ->orderBy('average_rating', 'desc')
                ->take(10)
                ->get();
<<<<<<< HEAD

=======
 
>>>>>>> ajax-smart-search
            $featuredProducts = Product::active()
                ->where('is_featured', true)
                ->with(['categories', 'user'])
                ->latest()
                ->take(8)
                ->get();
<<<<<<< HEAD

            // Call recommendation logic (simplified for home)
            $recommendedProducts = (new RecommendationController())->getRecommendations(auth()->id(), 4);

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
=======
 
            // Call recommendation logic (simplified for home)
            $recommendedProducts = (new RecommendationController())->getRecommendations(auth()->id(), 4);
 
            return view('pages.home', compact('bestsellers', 'featuredProducts', 'recommendedProducts'));
        }
 
        return view('pages.catalog.index', compact('products', 'categories', 'vendors'));
    }
 
    // =========================================================
    // AJAX SMART SEARCH — modifié + fuzzySearch() ajouté
    // =========================================================
 
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:1|max:100']);
 
        $term = trim($request->q);
 
        // Étape 1 : recherche LIKE partielle classique (WHERE name LIKE %term%)
        $products = Product::active()
            ->search($term)
            ->with('categories')
            ->take(8)
            ->get(['id', 'title', 'slug', 'price', 'sale_price', 'image']);
 
        // Étape 2 : si aucun résultat et terme assez long → fuzzy Levenshtein
        if ($products->isEmpty() && mb_strlen($term) >= 3) {
            $products = $this->fuzzySearch($term);
        }
 
        return response()->json([
            'results' => $products->map(fn($p) => [
                'id'        => $p->id,
                'title'     => $p->title,
                'price'     => number_format($p->effective_price, 2),
                'url'       => route('products.show', $p),
                'image_url' => $p->image_url,
            ]),
            'total'   => $products->count(),
            'query'   => $term,
        ]);
    }
 
    /**
     * Fuzzy search via distance de Levenshtein pour tolérer les fautes de frappe.
     * Exemple : "iphnoe" → trouve "iPhone", "sasmung" → "Samsung"
     */
    private function fuzzySearch(string $term): \Illuminate\Support\Collection
    {
        $termLower = mb_strtolower($term);
        $termLen   = mb_strlen($termLower);
 
        // Charger les produits actifs (limité à 300 pour la performance)
        $allProducts = Product::active()
            ->select(['id', 'title', 'slug', 'price', 'sale_price', 'image'])
            ->take(300)
            ->get();
 
        $scored = $allProducts->map(function ($product) use ($termLower, $termLen) {
            $titleLower = mb_strtolower($product->title);
 
            // Correspondance exacte partielle → distance 0 (priorité maximale)
            if (str_contains($titleLower, $termLower)) {
                return ['product' => $product, 'distance' => 0];
            }
 
            // Comparer terme contre chaque mot du titre
            $words   = preg_split('/[\s\-_]+/', $titleLower);
            $minDist = PHP_INT_MAX;
 
            foreach ($words as $word) {
                $wordLen = mb_strlen($word);
                if ($wordLen < 2) continue;
                // Ignorer les mots de longueur trop différente (évite le bruit)
                if (abs($wordLen - $termLen) > 3) continue;
 
                $dist    = levenshtein($termLower, $word);
                $minDist = min($minDist, $dist);
            }
 
            return ['product' => $product, 'distance' => $minDist];
        });
 
        // Seuil de tolérance : 2 pour termes courts (≤5 chars), 3 pour termes longs
        $maxDistance = $termLen <= 5 ? 2 : 3;
 
        return $scored
            ->filter(fn($item) => $item['distance'] <= $maxDistance)
            ->sortBy('distance')
            ->take(8)
            ->pluck('product');
    }
}
 
>>>>>>> ajax-smart-search
