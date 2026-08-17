<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function show(string $slug): View
    {
        $author = User::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $guides = $author->guides()->published()->orderByDesc('published_at')->get();

        return view('pages.author', compact('author', 'guides'));
    }
}
