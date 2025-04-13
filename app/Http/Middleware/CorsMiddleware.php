<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('CorsMiddleware hit for method: ' . $request->getMethod() . ', URL: ' . $request->url());

        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin', env('FRONTEND_URL', 'http://localhost:5173'));
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept, Access-Control-Allow-Headers, Access-Control-Request-Method, Access-Control-Request-Headers, Authorization");
        //        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Origin, Accept, Authorization');
        $response->headers->set('Access-Control-Max-Age', 86400);
        if ($request->getMethod() === 'OPTIONS') {
            return new Response('', 204, $response->headers->all());
        }

        return $response;
    }
}
