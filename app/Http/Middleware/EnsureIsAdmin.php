<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    private const ALLOWED_ROLES = ['super-admin', 'content-manager', 'finance-manager', 'support'];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasAnyRole(self::ALLOWED_ROLES)) {
            abort(403);
        }

        return $next($request);
    }
}
