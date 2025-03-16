<?php

use Illuminate\Support\Facades\DB;

function generateSlug(string $name, $modelTranslation, $locale): string
{
    // Transliterate Cyrillic and other characters to Latin script
    $slug = transliterator_transliterate('Any-Latin; Latin-ASCII', $name);
    
    // Convert to lowercase
    $slug = strtolower($slug);
    
    // Replace non-alphanumeric characters with hyphens
    $slug = preg_replace('/[^a-z0-9\s-]/u', '', $slug);
    
    // Replace spaces with hyphens
    $slug = preg_replace('/\s+/u', '-', $slug);
    
    // Trim hyphens from the beginning and end
    $slug = trim($slug, '-');
    
    // Check if the slug is in the model
    $originalSlug = $slug;
    $count = 1;
    // Check if the slug exists in the database
    while (DB::table($modelTranslation)->where('slug', $slug)->where('locale', '=', $locale)->exists()) {
        $slug = $originalSlug.'-'.$count;
        $count++;
    }
    
    return $slug;
}