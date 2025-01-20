<?php

function generateSlug(string $name): string
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
    
    return $slug;
}