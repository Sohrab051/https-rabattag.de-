<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'locale' => \App\Http\Middleware\SetLocale::class,
            'admin' => \App\Http\Middleware\EnsureIsAdmin::class,
            'can-manage-categories' => \App\Http\Middleware\EnsureCanManageCategories::class,
            'can-run-awin-sync' => \App\Http\Middleware\EnsureCanRunAwinSync::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SyncLocaleFromSession::class,
        ]);

        // Built purely from the request path (not route()/URL::defaults) so it
        // works even when 'auth' fires before our 'locale' middleware sets the
        // locale default, e.g. a guest hitting a protected route cold.
        $middleware->redirectGuestsTo(function (Request $request) {
            $locale = in_array($request->segment(1), \App\Http\Middleware\SetLocale::SUPPORTED_LOCALES, true)
                ? $request->segment(1)
                : 'en';

            return "/{$locale}/login";
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
