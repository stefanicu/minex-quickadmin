<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Add HSTS Header
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        
        // Add Content Security Policy (CSP)
        
        $nonce = bin2hex(random_bytes(16)); // Generate a random nonce
        
        $response->headers->set('Content-Security-Policy',
            "default-src 'none'; ".
            "connect-src 'self' https://region1.analytics.google.com https://www.google-analytics.com/ https://maps.googleapis.com https://cdnjs.cloudflare.com https://maps.googleapis.com https://www.gstatic.com/recaptcha/ https://www.google.com/recaptcha/ https://www.google-analytics.com/ https://www.googletagmanager.com;".
            "script-src 'self' 'nonce-{$nonce}' https://cdnjs.cloudflare.com https://maps.googleapis.com https://www.gstatic.com/recaptcha/ https://www.google.com/recaptcha/ https://www.google-analytics.com/ https://www.googletagmanager.com; ".
            "object-src 'self' https://maps.googleapis.com; ".
            "style-src 'self' https://cdnjs.cloudflare.com https://fonts.googleapis.com https://maps.googleapis.com 'sha256-mmA4m52ZWPKWAzDvKQbF7Qhx9VHCZ2pcEdC0f9Xn/Po='; ".
            "img-src 'self' data: https://maps.googleapis.com https://www.google.ro/; ".
            "font-src 'self' https://fonts.gstatic.com; ".
            "frame-ancestors 'self' https://maps.googleapis.com; ".
            "base-uri 'self';".
            "form-action 'self';"
        );
        
        // Pass the nonce to the request for later use
        $request->attributes->set('csp_nonce', $nonce);
        
        // Add X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Add X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Add Referrer Policy
        $response->headers->set('Referrer-Policy', 'no-referrer');
        
        // Add CORP header
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        
        return $response;
    }
}
