<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChatGPTService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TranslationGranularController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('translation_center_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $locale = $request->input('language');
        $model_translation = $request->input('model_translation');
        $foreign_key = $request->input('foreign_key');;
        $id = $request->input('id');
        
        // Check if the locale is 'en' (English)
        if ($locale === 'en') {
            // If the locale is 'en', we only need Romanian records for translation
            $record = DB::table($model_translation)
                ->whereIn('locale', ['ro'])// Fetch only Romanian records for translation into English
                ->where($foreign_key, '=', $id)
                ->first();
        } else {
            // Otherwise, fetch both English and the current locale records
            $record = DB::table($model_translation)
                ->whereIn('locale', ['en']) // Get both English and the current locale records
                ->where($foreign_key, '=', $id)
                ->first();
        }
        
        // Get all columns from the translation table dynamically (excluding id, foreign_key, and locale)
        $columns = Schema::getColumnListing($model_translation);
        $columns = array_diff($columns, ['id', $foreign_key, 'locale', 'online']); // Exclude the unwanted columns
        
        // Determine source locale based on the current locale
        $sourceLocale = ($locale === 'en') ? 'ro' : 'en';
        
        // Check if the record for the target language exists (current locale)
        $existingRecord = DB::table($model_translation)
            ->where('locale', $locale)
            ->where($foreign_key, '=', $id)
            ->first();
        
        if ($existingRecord) {
            // Record exists, so update empty fields
            foreach ($columns as $column) {
                // Check if the target field is empty and the source field is not empty
                Log::info('$existingRecord->'.$column.': '.$existingRecord->{$column});
                Log::info('$record->'.$column.': '.$record->{$column});
                Log::info('empty($existingRecord->'.$column.'): '.empty($existingRecord->{$column}));
                
                if (empty($existingRecord->{$column}) && ! empty($record->{$column})) {
                    // Use the source field value to translate (depending on the source locale)
                    if ($column === 'slug') {
                        $translatedValue = generateSlug($existingRecord->name);
                    } else {
                        $translatedValue = ChatGPTService::translate($record->{$column}, $locale, $sourceLocale);
                    }
                    
                    // Update the record in the translation table with the translated value
                    DB::table($model_translation)
                        ->where('id', $existingRecord->id)
                        ->update([$column => $translatedValue]);
                }
            }
        } else {
            // No record exists for the current locale, so create a new one
            $newRecordData = [
                $foreign_key => $id,
                'locale' => $locale,
            ];
            
            // For each column, if the source field is not empty, translate and insert the value
            foreach ($columns as $column) {
                // If the source field exists and has a value, translate it
                if ( ! empty($record->{$column})) {
                    // Translate from source language (Romanian for en, or English for others)
                    if ($column === 'slug') {
                        $newRecordData['slug'] = generateSlug($newRecordData['name']);
                    } else {
                        $translatedValue = app(ChatGPTService::class)->translate($record->{$column}, $locale, $sourceLocale);
                        $newRecordData[$column] = $translatedValue;
                    }
                } else {
                    // If there is no value in the source, we can set it to null or some default value if needed
                    $newRecordData[$column] = null;
                }
            }
            
            // Insert the new record with all translated fields
            DB::table($model_translation)->insert($newRecordData);
        }
        
        return redirect()->back()->with('success', __('Translations updated successfully for ').$locale);
    }
}
