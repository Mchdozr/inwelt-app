<?php

namespace App\Providers;

use App\Models\Category;
use App\Support\SiteCache;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('layouts.app', function ($view): void {
            $view->with('navCategories', Cache::remember('nav_categories', SiteCache::TTL, fn () =>
                Category::whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort')
                    ->limit(8)
                    ->get()
            ));
        });
    }
}
