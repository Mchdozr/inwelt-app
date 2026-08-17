<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\User;
use Illuminate\View\View;

class GuideController extends Controller
{
    public function index(): View
    {
        $guides = Guide::published()
            ->with(['author', 'category'])
            ->orderByDesc('published_at')
            ->get();

        return view('pages.guides.index', compact('guides'));
    }

    public function show(string $slug): View
    {
        $guide = Guide::published()
            ->with(['author', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedGuides = Guide::published()
            ->where('id', '!=', $guide->id)
            ->when($guide->category_id, fn ($query) => $query->where('category_id', $guide->category_id))
            ->limit(3)
            ->get();

        return view('pages.guides.show', [
            'guide' => $guide,
            'relatedGuides' => $relatedGuides,
            'relatedProducts' => $guide->relatedProducts(),
        ]);
    }
}
