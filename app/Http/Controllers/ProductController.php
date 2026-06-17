<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\ProductFilters;
use App\Support\SiteCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = $this->sidebarCategories();
        $products = $this->filteredProducts($request)->paginate(12)->withQueryString();

        $activeCategory = $request->filled('kategori')
            ? Category::where('slug', $request->kategori)->first()
            : null;

        if ($this->wantsGridItemsPartial($request)) {
            return $this->gridItemsResponse($products);
        }

        if ($this->wantsListingPartial($request)) {
            return view('partials.products-listing', compact('products'));
        }

        return view('pages.products', compact('categories', 'products', 'activeCategory'));
    }

    public function category(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories = $this->sidebarCategories();
        $activeCategory = $category;

        $catIds = $category->children->pluck('id')->prepend($category->id);

        $query = Product::with('category')
            ->whereIn('category_id', $catIds)
            ->where('is_active', true);

        if ($request->boolean('avantajli')) {
            $query->where('is_advantageous', true);
        }

        $products = $query->orderBy('sort')->paginate(12)->withQueryString();

        if ($this->wantsGridItemsPartial($request)) {
            return $this->gridItemsResponse($products);
        }

        if ($this->wantsListingPartial($request)) {
            return view('partials.products-listing', compact('products'));
        }

        return view('pages.products', compact('category', 'categories', 'products', 'activeCategory'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['category', 'images', 'specs', 'useCases'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->orderBy('sort')
            ->limit(4)
            ->get();

        return view('pages.product-detail', compact('product', 'related'));
    }

    private function filteredProducts(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true);

        if ($request->filled('kategori')) {
            $cat = Category::where('slug', $request->kategori)->firstOrFail();
            $catIds = $cat->children->pluck('id')->prepend($cat->id);
            $query->whereIn('category_id', $catIds);
        }

        $filterSlug = $request->filled('filtre')
            ? $request->filtre
            : ProductFilters::araToSlug($request->input('ara'));

        if ($filterSlug && ProductFilters::isValid($filterSlug)) {
            ProductFilters::apply($query, $filterSlug);
        } elseif ($request->filled('ara')) {
            $term = '%'.$request->ara.'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('summary', 'like', $term);
            });
        }

        if ($request->boolean('avantajli')) {
            $query->where('is_advantageous', true);
        }

        return $query->orderBy('sort');
    }

    private function wantsListingPartial(Request $request): bool
    {
        return $request->ajax() || $request->query('partial') === 'products-listing';
    }

    private function wantsGridItemsPartial(Request $request): bool
    {
        return $request->query('partial') === 'products-grid-items';
    }

    private function gridItemsResponse($products)
    {
        return response()->json([
            'html' => view('partials.products-grid-items', compact('products'))->render(),
            'current_page' => $products->currentPage(),
            'has_more' => $products->hasMorePages(),
        ]);
    }

    private function sidebarCategories()
    {
        return Cache::remember('product_sidebar_categories', SiteCache::TTL, fn () =>
            Category::whereNull('parent_id')
                ->where('is_active', true)
                ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort')])
                ->orderBy('sort')
                ->get()
        );
    }
}
