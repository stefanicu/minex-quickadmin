<?php

if ( ! function_exists('routeWithLang')) {
    /**
     * Get the route name with language prefix
     *
     * @param  string  $baseRouteName  The base route name (e.g., 'product')
     * @param  string  $lang  The language code (e.g., 'en', 'ro')
     * @return string The full route name with language prefix (e.g., 'product.en')
     */
    function routeWithLang(string $baseRouteName, string $lang): string
    {
// Validate that the language is valid
        $availableLocales = config('translatable.locales'); // or an array of available languages
        if ( ! in_array($lang, $availableLocales)) {
            return ''; // Or return a default route if the language is not valid
        }

// Return the route name with the language prefix
        return $baseRouteName.'.'.$lang;
    }
}