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
        $mapping = [
            'en' => 'EN', // English - USA (or use GB for United Kingdom)
            'ro' => 'RO', // Romanian - Romania
            'bg' => 'BG', // Bulgarian - Bulgaria
            'lt' => 'LT', // Lithuanian - Lithuania
            'lv' => 'LV', // Latvian - Latvia
            'et' => 'EE', // Estonian - Estonia
            'sr' => 'RS', // Serbian - Serbia
            'hr' => 'HR', // Croatian - Croatia
            'sl' => 'SI', // Slovenian - Slovenia
            'bs' => 'BA', // Bosnian - Bosnia and Herzegovina
            'mk' => 'MK', // Macedonian - North Macedonia
            'hu' => 'HU', // Hungarian - Hungary
            'uk' => 'UA', // Ukrainian - Ukraine
        ];
        
        return $mapping[$languageCode] ?? null; // Return country code or null if not found
    }
}