<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Runs on every web request (including Livewire's /livewire/update background
 * requests, which bypass the {locale}-prefixed route group) so that route()
 * calls needing a "locale" parameter still resolve correctly from the session
 * set by SetLocale.
 */
class SyncLocaleFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (in_array($locale, SetLocale::SUPPORTED_LOCALES, true)) {
            app()->setLocale($locale);
            URL::defaults(['locale' => $locale]);
        }

        return $next($request);
    }
}
