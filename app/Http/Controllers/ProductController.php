<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort')])
            ->orderBy('sort')
            ->get();

        $query = Product::with('category')
            ->where('is_active', true);

        if ($request->filled('kategori')) {
            $cat = Category::where('slug', $request->kategori)->firstOrFail();
            $catIds = $cat->children->pluck('id')->prepend($cat->id);
            $query->whereIn('category_id', $catIds);
        }

        if ($request->filled('ara')) {
            $query->where('name', 'like', '%' . $request->ara . '%');
        }

        $products = $query->orderBy('sort')->paginate(12)->withQueryString();

        $activeCategory = $request->filled('kategori')
            ? Category::where('slug', $request->kategori)->first()
            : null;

        return view('pages.products', compact('categories', 'products', 'activeCategory'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $catIds = $category->children->pluck('id')->prepend($category->id);

        $products = Product::with('category')
            ->whereIn('category_id', $catIds)
            ->where('is_active', true)
            ->orderBy('sort')
            ->paginate(12);

        return view('pages.products', compact('category', 'products'));
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
}
