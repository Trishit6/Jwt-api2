<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtCookieMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('JWT COOKIE MIDDLEWARE HIT');

        $token = $request->cookie('jwt_token');

        \Log::info('JWT COOKIE VALUE', [
            'jwt_token' => $token,
        ]);

        if ($token) {
            $request->headers->set('Authorization', 'Bearer '.$token);

            \Log::info('AUTH HEADER SET', [
                'authorization' => $request->header('Authorization'),
            ]);
        } else {
            \Log::warning('NO JWT COOKIE FOUND');
        }

        return $next($request);
    }
}
