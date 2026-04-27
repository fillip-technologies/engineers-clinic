<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     * Redirects users to their appropriate dashboard based on role.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return $next($request);
        }

        $roleName = $request->user()->role->name ?? null;

        // Store intended URL for redirect after login
        if (!$roleName) {
            session(['url.intended' => $request->fullUrl()]);
        }

        return $next($request);
    }
}