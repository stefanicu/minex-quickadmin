<?php

function generateSlug(string $name): string
{
    // Normalize the string to decompose special characters (NFD normalization)
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
    
    // Convert to lowercase
    $slug = strtolower($slug);
    
    // Replace non-alphanumeric characters (excluding diacritics) with hyphens
    $slug = preg_replace('/[^a-z0-9]+/u', '-', $slug);
    
    // Trim hyphens from the beginning and end
    $slug = trim($slug, '-');
    
    return $slug;
}