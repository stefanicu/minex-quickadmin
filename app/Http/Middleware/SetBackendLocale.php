<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetBackendLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next): Response
    {
        // Check for 'lang' query parameter in the URL
        $locale = $request->query('lang', $request->input('locale', Session::get('backend_locale', config('app.fallback_locale', 'en'))));
        
        // Validate the locale against configured locales
        if (in_array($locale, config('translatable.locales'))) {
            App::setLocale($locale);
            Session::put('backend_locale', $locale); // Persist locale in session
        } else {
            // Fallback to default locale if invalid
            $fallback = config('app.fallback_locale', 'en');
            App::setLocale($fallback);
            Session::put('backend_locale', $fallback); // Persist fallback locale
        }
        
        return $next($request);
    }
}
