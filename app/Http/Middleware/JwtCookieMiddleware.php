<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtCookieMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasCookie('jwt_token')) {
            $request->headers->set('Authorization', 'Bearer '.$request->cookie('jwt_token'));
        }

        return $next($request);
    }
}
