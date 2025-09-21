<?php

use Illuminate\Support\Facades\DB;

function slugDuplicateCount(?string $slug, $modelTranslation, $locale, $idColumn, $excludeId = null): string
{
    // Check if the slug is in the model __
    $originalSlug = $slug;
    $count = 1;
    
    // Build the query to check for slug existence
    $query = DB::table($modelTranslation)->where('slug', $slug)->where('locale', '=', $locale);
    
    // If an ID to exclude is provided, add it to the query
    if ($excludeId !== null) {
        $query->where($idColumn, '!=', $excludeId);
    }
    
    // Check if the slug exists in the database
    while ($query->exists()) {
        // If it exists, we need to reset the query for the next iteration
        // with the new slug, but keep the exclusion.
        $slug = $originalSlug.'-'.$count;
        $count++;
        
        $query = DB::table($modelTranslation)->where('slug', $slug)->where('locale', '=', $locale);
        if ($excludeId !== null) {
            $query->where($idColumn, '!=', $excludeId);
        }
    }
    
    return $slug;
}