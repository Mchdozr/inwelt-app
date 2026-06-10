<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort')
            ->limit(6)
            ->get();

        $featured = Product::with('category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->orderBy('sort')
            ->limit(6)
            ->get();

        return view('pages.home', compact('categories', 'featured'));
    }
}
