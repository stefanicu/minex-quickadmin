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
        // Check if the locale is 'en' (English)
        if ($locale === 'en') {
            // If the locale is 'en', fetch Romanian record for translate source
            $record = DB::table($modelTranslation)
                ->whereIn('locale', ['ro'])// Fetch only Romanian record for translation into English
                ->where($foreignKey, '=', $id)
                ->first();
        } else {
            // Otherwise, fetch English record for translate source
            $record = DB::table($modelTranslation)
                ->whereIn('locale', ['en']) // Get the English record to be translated
                ->where($foreignKey, '=', $id)
                ->first();
        }
        
        // Get all columns from the translation table dynamically (excluding id, foreign_key, and locale)
        $columns = Schema::getColumnListing($modelTranslation);
        $columns = array_diff($columns, ['id', $foreignKey, 'locale', 'online']); // Exclude the unwanted columns
        
        // Determine source locale based on the current locale
        $sourceLocale = ($locale === 'en') ? 'ro' : 'en';
        
        // Check if the record for the target language exists (current locale)
        $existingRecord = DB::table($modelTranslation)
            ->where('locale', $locale)
            ->where($foreignKey, '=', $id)
            ->first();
        
        if ( ! $existingRecord) {
            // Initialize the translated name
            $translatedName = isset($record->name) ? ChatGPTService::translate($record->name, $locale, $sourceLocale) : null;
            
            // Build the base record data
            $newRecordData = [
                $foreignKey => $id,
                'locale' => $locale,
            ];
            
            // Add name if it's translated
            if ($translatedName) {
                $newRecordData['name'] = $translatedName;
            }
            
            // Generate and add the slug if name is set and translated
            if ($translatedName && isset($record->slug)) {
                $newRecordData['slug'] = generateSlug($translatedName);
            }
            
            // Insert the new record
            DB::table($modelTranslation)->insert($newRecordData);
        }
        
        foreach ($columns as $column) {
            $value = $record->{$column};
            // Check if the target field is empty and the source field is not empty
            if (empty($existingRecord->{$column}) && ! empty($value)) {
                if ($column != 'slug') {
                    // Dispatch the job to the queue
                    TranslateBulkUpdate::dispatch($modelTranslation, $foreignKey, $id, $locale, $column, $value);
                }
            }
        }
    }
}