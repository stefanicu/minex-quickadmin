<?php

namespace App\Traits;

use App\Jobs\TranslateBulkUpdate;
use App\Services\ChatGPTService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait TranslateWithQueue
{
    public function translateQueueByColumns($modelTranslation, $foreignKey, $locale, $id)
    {
        // Determine source locale based on the current locale __
        $sourceLocale = ($locale === 'en') ? 'ro' : 'en';
        
        // Fetch the source record only once (Romanian or English, depending on the locale)
        $record = DB::table($modelTranslation)
            ->whereIn('locale', [$sourceLocale]) // Fetch only source language record
            ->where($foreignKey, '=', $id)
            ->first();
        
        if ( ! $record) {
            // Early exit if no record found for translation source
            return;
        }
        
        // Get all columns from the translation table dynamically (excluding id, foreign_key, and locale)
        $columns = Schema::getColumnListing($modelTranslation);
        $columns = array_diff($columns, ['id', $foreignKey, 'locale', 'online']); // Exclude the unwanted columns
        
        // Check if a record for the target language exists
        $existingRecord = DB::table($modelTranslation)
            ->where('locale', $locale)
            ->where($foreignKey, '=', $id)
            ->first();
        
        $newRecordData = [
            $foreignKey => $id,
            'locale' => $locale,
        ];
        
        if ($existingRecord === null) {
            // If no existing record, prepare to insert
            $translatedName = isset($record->name) ? ChatGPTService::translate($record->name, $locale, $sourceLocale) : null;
            
            // Add name if translated
            if ($translatedName) {
                $newRecordData['name'] = $translatedName;
            }
            
            // Generate and add the slug if name is set and translated
            if ($translatedName) {
                if ( ! $record->slug) {
                    $slugGenerated = slugGenerate($translatedName, $locale);
                }
                $newRecordData['slug'] = slugDuplicateCount($slugGenerated, $modelTranslation, $locale, $foreignKey, $id);
            }
            
            // Insert the new record (if no existing record)
            DB::table($modelTranslation)->insert($newRecordData);
        }
        
        // Now, update each column if needed and dispatch jobs in bulk
        $columnsToUpdate = [];
        foreach ($columns as $column) {
            $value = $record->{$column};
            
            // If the target field is empty and the source field has a value
            if (empty($existingRecord->{$column}) && ! empty($value) && $column !== 'slug') {
                $columnsToUpdate[] = $column;
                // Dispatch the job to the queue for each column
                TranslateBulkUpdate::dispatch($modelTranslation, $foreignKey, $id, $locale, $column, $value);
            }
        }
        
        // ✅ După ce ai determinat coloanele care trebuie traduse
        if ( ! empty($columnsToUpdate) && $modelTranslation === 'blog_translations') {
            DB::table($modelTranslation)
                ->where($foreignKey, $id)
                ->where('locale', $locale)
                ->update(['online' => 1]);
        }
    }
}