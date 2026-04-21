<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            abort(401);
        }

        if (! auth()->user()->isVendor() && ! auth()->user()->isAdmin()) {
            abort(403, 'Only vendors can access this area.');
        }

        return $next($request);
    }
}
