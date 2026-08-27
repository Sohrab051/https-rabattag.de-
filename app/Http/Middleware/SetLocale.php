<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED_LOCALES = ['de', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            abort(404);
        }

        app()->setLocale($locale);
        session(['locale' => $locale]);
        URL::defaults(['locale' => $locale]);

        if ($request->user() && $request->user()->locale_preference !== $locale) {
            $request->user()->update(['locale_preference' => $locale]);
        }

        return $next($request);
    }

    public static function resolvePreferredLocale(Request $request): string
    {
        if ($request->user()?->locale_preference) {
            return $request->user()->locale_preference;
        }

        if ($request->hasCookie('locale') && in_array($request->cookie('locale'), self::SUPPORTED_LOCALES, true)) {
            return $request->cookie('locale');
        }

        return 'de';
    }
}
