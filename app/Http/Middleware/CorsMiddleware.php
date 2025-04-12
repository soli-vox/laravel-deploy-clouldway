<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $frontendUrl = env('APP_FRONTEND_URL', 'https://pvms-test-frontend.netlify.app');

        \Log::info('CORS Headers Set', [
            'Origin' => $frontendUrl,
            'Method' => $request->getMethod(),
            'URL' => $request->fullUrl(),
        ]);

        $response->headers->set('Access-Control-Allow-Origin', $frontendUrl);
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');

        return $response;
    }
}
