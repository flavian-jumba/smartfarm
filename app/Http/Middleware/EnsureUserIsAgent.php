<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAgent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Allow both agents and admins (admins can view agent panel)
        if (! $user || ! in_array($user->role, ['agent', 'admin'])) {
            abort(403, 'Access denied. Agent or Admin privileges required.');
        }

        return $next($request);
    }
}
