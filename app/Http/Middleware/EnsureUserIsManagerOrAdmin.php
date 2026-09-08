<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsManagerOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->user()?->role, ['manager', 'admin'])) {
            abort(403, 'Доступ заборонено.');
        }

        return $next($request);
    }
}
