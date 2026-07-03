<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: session -> cookie -> app default
        if (session()->has('locale')) {
            $locale = session()->get('locale');
        } elseif ($request->cookie('locale')) {
            $locale = $request->cookie('locale');
        } else {
            $locale = config('app.locale');
        }

        if (in_array($locale, ['en', 'id'])) {
            app()->setLocale($locale);
            // Ensure session is also set for consistency
            session()->put('locale', $locale);
        }

        return $next($request);
    }
}
