<?php

if ( ! function_exists('languageToCountryCode')) {
    /**
     * Convert a language code to a country code.
     *
     * @param  string  $languageCode
     * @return string|null
     */
    function languageToCountryCode(string $languageCode): ?string
    {
        $mapping = config('panel.available_languages');
        
        return $mapping[$languageCode] ?? null; // Return country code or null if not found
    }
}