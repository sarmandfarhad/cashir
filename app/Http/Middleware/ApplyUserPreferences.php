<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyUserPreferences
{
    /**
     * Apply session-backed display preferences to the current request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', config('app.locale', 'en'));

        if (! in_array($locale, ['en', 'ar'], true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        $theme = $request->session()->get('theme', 'light');

        if (! in_array($theme, ['light', 'dark'], true)) {
            $request->session()->put('theme', 'light');
        }

        return $next($request);
    }
}
