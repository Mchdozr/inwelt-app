<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach([
    route('products.index'),
    route('home'),
    route('about'),
    route('contact'),
    route('faq'),
    route('guides.index'),
    route('legal.editorial'),
    route('legal.privacy'),
    route('legal.kvkk'),
    route('legal.terms'),
    route('legal.cookies'),
] as $loc)
    <url>
        <loc>{{ $loc }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
@endforeach
@foreach($authors as $author)
    <url>
        <loc>{{ route('authors.show', $author->slug) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>
@endforeach
</urlset>
