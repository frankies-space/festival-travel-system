<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPlanner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isPlanner()) {
            abort(403, 'Alleen planners hebben toegang tot deze pagina.');
        }

        return $next($request);
    }
}
