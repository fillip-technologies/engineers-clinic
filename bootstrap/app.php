<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load custom admin routes without auth middleware
            Route::middleware('web')
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth'                   => \App\Http\Middleware\RedirectBasedOnRole::class,
            'role'                   => \App\Http\Middleware\CheckRole::class,
            'college.payment.guard'  => \App\Http\Middleware\RequireCollegePayment::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'course/*/reserve',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
