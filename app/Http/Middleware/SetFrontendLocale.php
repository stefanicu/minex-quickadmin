<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetFrontendLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('lang'); // Extract language from URL
        
        if ($locale === null) {
            $locale = $request->segment(1); // Get the first segment of the URL
            if ($locale === null) {
                // Redirect to default language if not provided
                return redirect('/en'.$request->getPathInfo()); // Return the RedirectResponse
            }
        }
        
        if (in_array($locale, config('translatable.locales'))) {
            App::setLocale($locale);
            Session::put('frontend_locale', $locale); // Store frontend locale
        } else {
            App::setLocale(Session::get('frontend_locale', 'en')); // Fallback to stored locale
        }
        
        return $next($request); // Proceed to the next middleware or the request handler
    }
}