<?php

if ( ! function_exists('currentRouteChangeName')) {
    /**
     * Get the route name with language prefix
     *
     * @param  string  $lang  The language code (e.g., 'en', 'ro')
     * @return string The full route name with language prefix (e.g., 'product.en')
     */
    function currentRouteChangeName($lang): string
    {
        // Get the current route name (e.g., 'product.en')
        $currentRouteName = Route::currentRouteName();
        
        // Split the route name by the dot
        $parts = explode('.', $currentRouteName);
        
        // Get the base route name (first part of the split)
        $baseRouteName = $parts[0];
        
        // Return the route name with the language prefix
        return $baseRouteName.'.'.$lang;
    }
}