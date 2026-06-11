<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPublicCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $request->isMethod('GET')
            && $response->isSuccessful()
            && $this->shouldCachePublicly($request)
        ) {
            $response->headers->set('Cache-Control', 'public, max-age=3600, s-maxage=3600');
        }

        return $response;
    }

    private function shouldCachePublicly(Request $request): bool
    {
        if ($request->is('admin', 'admin/*')) {
            return false;
        }

        // Katalog ve ana sayfa admin güncellemelerinden sonra CDN'de bayat kalmasın.
        if ($request->is('/', 'urunler', 'kategori/*', 'urun/*', 'iletisim')) {
            return false;
        }

        return true;
    }
}
