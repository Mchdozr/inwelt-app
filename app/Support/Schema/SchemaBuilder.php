<?php

namespace App\Support\Schema;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Support\ProductMarketplace;
use App\Support\SiteContact;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class SchemaBuilder
{
    /**
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        $sameAs = array_values(array_filter([
            Setting::get('social_instagram') ?: config('seo.same_as.instagram'),
            Setting::get('social_linkedin'),
            Setting::get('social_youtube'),
            config('seo.same_as.kacmasa'),
            config('seo.same_as.trendyol'),
            config('seo.same_as.hepsiburada'),
        ], fn ($url) => self::isPublicProfileUrl(is_string($url) ? $url : null)));

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'INWELT',
            'url' => url('/'),
            'logo' => asset('images/inwelt-logo.png'),
            'email' => SiteContact::email(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => SiteContact::address(),
                'addressCountry' => 'TR',
            ],
            'foundingDate' => config('seo.founding_date'),
            'sameAs' => $sameAs,
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => SiteContact::email(),
                'availableLanguage' => ['Turkish'],
            ]],
            'brand' => [
                '@type' => 'Brand',
                'name' => 'INWELT',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function website(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'INWELT',
            'url' => url('/'),
            'inLanguage' => 'tr-TR',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('products.index').'?ara={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * @param  list<array{name: string, url: string}>  $crumbs
     * @return array<string, mixed>
     */
    public static function breadcrumb(array $crumbs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($crumbs)->values()->map(fn ($crumb, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ])->all(),
        ];
    }

    /**
     * @param  list<array{question: string, answer: string}>  $faqs
     * @return array<string, mixed>|null
     */
    public static function faq(array $faqs): ?array
    {
        $items = collect($faqs)
            ->filter(fn ($faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->values();

        if ($items->isEmpty()) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq['answer']),
                ],
            ])->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function product(Product $product): array
    {
        $images = self::productImages($product);
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->seo_description ?: $product->summary ?: $product->name,
            'url' => route('products.show', $product->slug),
            'sku' => $product->resolvedSku(),
            'brand' => [
                '@type' => 'Brand',
                'name' => 'INWELT',
            ],
        ];

        if ($product->category) {
            $schema['category'] = $product->category->name;
        }

        if ($images !== []) {
            $schema['image'] = $images;
        }

        if (filled($product->gtin13)) {
            $schema['gtin13'] = $product->gtin13;
        }

        if ($product->rating_value && $product->rating_count) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $product->rating_value,
                'reviewCount' => (string) $product->rating_count,
            ];
        }

        $offers = self::productOffers($product);

        if ($offers !== []) {
            $prices = collect($offers)->pluck('price')->map(fn ($price) => (float) $price);
            $schema['offers'] = [
                '@type' => 'AggregateOffer',
                'priceCurrency' => 'TRY',
                'lowPrice' => number_format($prices->min(), 2, '.', ''),
                'highPrice' => number_format($prices->max(), 2, '.', ''),
                'offerCount' => count($offers),
                'availability' => 'https://schema.org/InStock',
                'offers' => $offers,
            ];
        }

        return $schema;
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return array<string, mixed>
     */
    public static function itemList(Category $category, Collection $products): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name,
            'url' => route('products.category', $category->slug),
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => $products->values()->map(fn (Product $product, int $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => route('products.show', $product->slug),
                    'name' => $product->name,
                ])->all(),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function article(Guide $guide): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => ['Article', 'BlogPosting'],
            'headline' => $guide->title,
            'description' => $guide->excerpt,
            'datePublished' => optional($guide->published_at)->toAtomString(),
            'dateModified' => optional($guide->updated_at)->toAtomString(),
            'inLanguage' => 'tr-TR',
            'mainEntityOfPage' => route('guides.show', $guide->slug),
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'INWELT',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/inwelt-logo.png'),
                ],
            ],
        ];

        if ($guide->cover_image) {
            $schema['image'] = self::absoluteMedia($guide->cover_image);
        }

        if ($guide->author) {
            $schema['author'] = self::person($guide->author);
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>
     */
    public static function person(User $user): array
    {
        $person = [
            '@type' => 'Person',
            'name' => $user->name,
        ];

        if ($user->slug) {
            $person['url'] = route('authors.show', $user->slug);
        }

        if ($user->bio) {
            $person['description'] = $user->bio;
        }

        if ($user->linkedin_url) {
            $person['sameAs'] = [$user->linkedin_url];
        }

        return $person;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function productOffers(Product $product): array
    {
        if (! $product->hasFreshMarketplacePrices()) {
            return [];
        }

        $validUntil = optional($product->prices_synced_at ?? now())->copy()->addDays(7)->toDateString();
        $offers = [];

        foreach ([
            ['kacmasa', 'Kacmasa', ProductMarketplace::kacmasaUrl($product) ?? ProductMarketplace::kacmasaStoreUrl($product->slug), $product->price],
            ['trendyol', 'Trendyol', ProductMarketplace::trendyolUrl($product), $product->trendyol_price],
            ['hepsiburada', 'Hepsiburada', ProductMarketplace::hepsiburadaUrl($product), $product->hepsiburada_price],
        ] as [$channel, $seller, $url, $price]) {
            if ($price === null || (float) $price <= 0) {
                continue;
            }

            $offers[] = [
                '@type' => 'Offer',
                'url' => $url,
                'price' => number_format((float) $price, 2, '.', ''),
                'priceCurrency' => 'TRY',
                'priceValidUntil' => $validUntil,
                'availability' => 'https://schema.org/InStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => $seller,
                ],
            ];
        }

        return $offers;
    }

    /**
     * @return list<string>
     */
    private static function productImages(Product $product): array
    {
        $paths = collect();

        if ($product->og_image) {
            $paths->push($product->og_image);
        }

        if ($product->cover_image) {
            $paths->push($product->cover_image);
        }

        if ($product->relationLoaded('images')) {
            $paths = $paths->merge($product->images->pluck('path'));
        }

        return $paths
            ->filter()
            ->unique()
            ->map(fn ($path) => self::absoluteMedia((string) $path))
            ->values()
            ->all();
    }

    public static function isPublicProfileUrl(?string $url): bool
    {
        if (! is_string($url) || $url === '' || $url === '#') {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        if ($host === '') {
            return false;
        }

        $bareHosts = [
            'linkedin.com',
            'www.linkedin.com',
            'youtube.com',
            'www.youtube.com',
            'youtu.be',
            'm.youtube.com',
        ];

        return ! (in_array($host, $bareHosts, true) && $path === '');
    }

    private static function absoluteMedia(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return url(Storage::url($path));
    }
}
