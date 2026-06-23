<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireCollegePayment
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role?->name === 'college') {
            $college = $user->college;

            if (! $college || $college->payment_status !== 'approved') {
                return redirect()->route('college.payment');
            }
        }

        return $next($request);
    }
}
