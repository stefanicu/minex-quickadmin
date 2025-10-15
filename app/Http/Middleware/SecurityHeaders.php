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
        // Add Content Security Policy (CSP)
        $nonce = bin2hex(random_bytes(16)); // Generate a random nonce
        
        session()->put('csp_nonce', $nonce);
        
        $response = $next($request);
        
        // Exclude admin area
        if ($request->is('admin/*') || $request->is('admin')) {
            return $response;
        }
        
        if (config('app.env') === 'production') {
            $response->headers->set('Content-Security-Policy',
                //                "default-src 'none'; ".
                //                "connect-src 'self' https://stats.g.doubleclick.net https://www.googleadservices.com https://region1.analytics.google.com https://region1.google-analytics.com https://www.google-analytics.com https://maps.googleapis.com https://www.gstatic.com https://cdnjs.cloudflare.com https://www.gstatic.com/recaptcha/ https://www.google.com/recaptcha/ https://www.googletagmanager.com https://*.google.com; ".
                //                "script-src 'self' 'nonce-{$nonce}' https://www.googleadservices.com https://cdnjs.cloudflare.com https://maps.googleapis.com https://maps.gstatic.com https://www.gstatic.com https://www.gstatic.com/recaptcha/ https://www.google.com/recaptcha/ https://www.google-analytics.com https://www.googletagmanager.com; ".
                //                "worker-src blob:; ".
                //                "child-src blob:; ".
                //                "object-src 'self' https://maps.googleapis.com; ".
                //                "style-src 'self' 'unsafe-inline' https://www.googleadservices.com https://cdnjs.cloudflare.com https://fonts.googleapis.com https://maps.googleapis.com https://maps.gstatic.com https://www.gstatic.com; ".
                //                "img-src 'self' data: blob: https://www.google.com https://www.googletagmanager.com https://googleads.g.doubleclick.net https://maps.googleapis.com https://maps.gstatic.com https://www.gstatic.com https://www.google.ro https://*.googleapis.com https://*.gstatic.com https://*.google.com https://*.ggpht.com https://*.googleusercontent.com; ".
                //                "font-src 'self' data: https://fonts.gstatic.com https://fonts.googleapis.com; ".
                //                "frame-ancestors 'self'; ".
                //                "base-uri 'self'; ".
                //                "frame-src 'self' https://www.googletagmanager.com https://www.google.com; ".
                //                "form-action 'self';"
                
                "default-src 'none'; ".
                "connect-src 'self' 'nonce-{$nonce}' data: https://stats.g.doubleclick.net https://www.googleadservices.com https://region1.analytics.google.com https://region1.google-analytics.com https://www.google-analytics.com https://maps.googleapis.com https://cdnjs.cloudflare.com https://www.gstatic.com/recaptcha/ https://www.google.com/recaptcha/ https://www.googletagmanager.com https://www.gstatic.com https://*.google.com; ".
                "script-src 'self' 'nonce-{$nonce}' 'unsafe-eval' https://www.googleadservices.com https://cdnjs.cloudflare.com https://maps.googleapis.com https://www.gstatic.com/recaptcha/ https://www.google.com/recaptcha/ https://www.google-analytics.com/ https://www.googletagmanager.com https://maps.gstatic.com; ".
                "worker-src blob:; ".
                "child-src blob:; ".
                "object-src 'self' https://maps.googleapis.com; ".
                "style-src 'self' 'unsafe-inline' https://www.googleadservices.com https://cdnjs.cloudflare.com https://fonts.googleapis.com https://maps.googleapis.com https://maps.gstatic.com https://www.gstatic.com; ".
                "img-src 'self' data: blob: https://www.google.com https://www.googletagmanager.com https://googleads.g.doubleclick.net https://maps.googleapis.com https://maps.gstatic.com https://www.gstatic.com https://www.google.ro https://*.googleapis.com https://*.gstatic.com https://*.google.com https://*.ggpht.com https://*.googleusercontent.com; ".
                "font-src 'self' data: https://fonts.gstatic.com https://fonts.googleapis.com; ".
                "frame-ancestors 'self' https://maps.googleapis.com; ".
                "base-uri 'self'; ".
                "frame-src 'self' https://www.googletagmanager.com/ https://www.google.com/; ".
                "form-action 'self'; "
            );
        }
        
        return $response;
    }
}