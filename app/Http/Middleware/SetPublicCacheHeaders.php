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
            && ! $request->is('admin', 'admin/*')
        ) {
            $response->headers->set('Cache-Control', 'public, max-age=3600, s-maxage=3600');
        }

        return $response;
    }
}
