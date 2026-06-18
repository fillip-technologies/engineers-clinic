<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect('/login');
        }

        if (($request->user()->role?->name ?? '') !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            return redirect('/dashboard')->with('error', 'You do not have permission to access this page.');
        }

        if (
            $role === 'college'
            && $request->user()->college?->payment_status !== 'approved'
            && ! $request->routeIs('college.payment', 'college.payment.store', 'college.payment.verify', 'college.settings', 'college.settings.update')
        ) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'College payment not approved.'], 403);
            }
            return redirect()->route('college.payment')
                ->with('error', 'Please complete college payment before accessing the dashboard.');
        }

        return $next($request);
    }
}
