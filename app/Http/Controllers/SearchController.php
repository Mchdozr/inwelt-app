<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    public function suggest(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return $this->jsonResponse($query, []);
        }

        $term = '%'.$query.'%';

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where(function ($builder) use ($term) {
                $builder->where('name', 'like', $term)
                    ->orWhere('summary', 'like', $term);
            })
            ->orderBy('sort')
            ->limit(8)
            ->get()
            ->map(fn (Product $product) => [
                'name' => $product->name,
                'slug' => $product->slug,
                'url' => route('products.show', $product->slug),
                'image' => $product->cover_image
                    ? Storage::url($product->cover_image)
                    : asset('images/product-fallback.png'),
                'price' => null,
                'category' => $product->category?->name,
                'summary' => $product->summary,
                'badge' => $product->badge,
            ])
            ->values();

        return $this->jsonResponse($query, $products);
    }

    private function jsonResponse(string $query, mixed $products): JsonResponse
    {
        return response()
            ->json([
                'query' => $query,
                'products' => $products,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
