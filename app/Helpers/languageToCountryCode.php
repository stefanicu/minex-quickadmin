<?php

if ( ! function_exists('languageToCountryCode')) {
    /**
     * Convert a language code to a country code.
     *
     * @param  string  $languageCode
     * @return string|null
     */
    function languageToCountryCode(string $languageCode, bool $names = false): ?string
    {
        $mapping = config('panel.available_languages');
        
        if ($names) {
            $mapping = config('panel.languages_names');
        }
        
        return $mapping[$languageCode] ?? null; // Return country code or null if not found
    }
}