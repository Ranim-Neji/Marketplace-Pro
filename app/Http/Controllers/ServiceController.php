<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if we are in vendor or admin context
        if (request()->routeIs('vendor.services.index')) {
            $services = Auth::user()
                ->services()
                ->with('category')
                ->latest()
                ->paginate(12);
            return view('pages.services.vendor.index', compact('services'));
        }

        if (request()->routeIs('admin.services.index')) {
            $services = Service::with(['user', 'category'])->latest()->paginate(20);
            return view('pages.services.admin.index', compact('services'));
        }

        // Public browse
        $query = Service::query()->with(['user', 'category']);
        
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($minPrice = $request->input('min_price')) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($request->boolean('available')) {
            $query->where('availability', true);
        }

        match ($request->input('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $services = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('pages.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('pages.services.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['availability'] = $request->boolean('availability', true);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'services');
        }

        $service = Service::create($data);

        $redirectRoute = Auth::user()->isAdmin() ? 'admin.services.index' : 'vendor.services.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load(['user', 'category']);
        
        // Suggest related services
        $relatedServices = Service::where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->where('availability', true)
            ->take(4)
            ->get();

        return view('pages.services.show', compact('service', 'relatedServices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        // Authorization
        if (Auth::id() !== $service->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();
        return view('pages.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        // Authorization
        if (Auth::id() !== $service->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $data = $request->validated();
        $data['availability'] = $request->boolean('availability');

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'), 'services');
        }

        $service->update($data);

        $redirectRoute = Auth::user()->isAdmin() ? 'admin.services.index' : 'vendor.services.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Authorization
        if (Auth::id() !== $service->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return back()->with('success', 'Service deleted successfully.');
    }

    private function uploadImage($file, string $folder): string
    {
        $filename = uniqid('svc_', true).'.'.$file->getClientOriginalExtension();
        $path = $folder.'/'.$filename;

        $image = Image::read($file);
        $image->scale(width: 800);
        Storage::disk('public')->put($path, $image->toJpeg(85));

        return $path;
    }
}
