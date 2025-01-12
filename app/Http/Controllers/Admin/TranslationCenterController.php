<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class TranslationCenterController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('translation_center_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        // Retrieve available locales from configuration
        $availableLanguages = config('translatable.locales');
        
        
        // Define models and their corresponding translation tables and foreign keys
        $models = [
            'sections' => ['table' => 'front_pages', 'translation_table' => 'front_page_translations', 'foreign_key' => 'front_page_id'],
            'applications' => ['table' => 'applications', 'translation_table' => 'application_translations', 'foreign_key' => 'application_id', 'filter' => ['online' => 1]],
            'categories' => ['table' => 'categories', 'translation_table' => 'category_translations', 'foreign_key' => 'category_id', 'filter' => ['online' => 1]],
            'brands' => ['table' => 'brands', 'translation_table' => 'brand_translations', 'foreign_key' => 'brand_id', 'filter' => ['online' => 1]],
        ];
        
        $data = [];
        
        // Loop through models and build queries dynamically
        foreach ($models as $key => $model) {
            $query = DB::table($model['table'])->selectRaw('COUNT(*) as count_total');
            
            // Add counts for each available language
            foreach ($availableLanguages as $locale) {
                $query->leftJoin("{$model['translation_table']} as t_{$locale}", function ($join) use ($locale, $model) {
                    $join->on("{$model['table']}.id", '=', "t_{$locale}.{$model['foreign_key']}")
                        ->where("t_{$locale}.locale", '=', $locale);
                    
                    // Apply additional filters if defined
                    if (isset($model['filter'])) {
                        foreach ($model['filter'] as $column => $value) {
                            $join->where("t_{$locale}.{$column}", '=', $value);
                        }
                    }
                })->selectRaw("COUNT(t_{$locale}.id) as count_{$locale}");
            }
            
            // Execute the query and store the result
            $data[$key] = $query->first();
        }
        
        return view('admin.translations.index', [
            'availableLanguages' => $availableLanguages,
            'data' => $data,
        ]);
    }
    
    
    public function strings(Request $request)
    {
        $languages = config('translatable.locales'); // Example: ['en', 'ro', 'bg']
        $basePath = resource_path('lang');
        $translations = [];
        $englishLangPath = "{$basePath}/en"; // English translations folder
        
        foreach ($languages as $lang) {
            $langPath = "{$basePath}/{$lang}";
            
            // Check if the language folder exists, if not create it
            if ( ! File::exists($langPath)) {
                File::makeDirectory($langPath, 0755, true);
            }
            
            // Copy missing files from the English version if the language folder exists
            $englishFiles = File::allFiles($englishLangPath);
            foreach ($englishFiles as $file) {
                $destination = "{$langPath}/".$file->getFilename();
                // Only copy the file if it doesn't already exist in the target language
                if ( ! File::exists($destination)) {
                    File::copy($file->getPathname(), $destination);
                }
            }
            
            // Now load translations for the language (including newly copied files if any)
            if (File::exists($langPath)) {
                $files = File::allFiles($langPath);
                
                foreach ($files as $file) {
                    $filename = $file->getFilenameWithoutExtension();
                    
                    // Use File::getRequire() to load the translations for the file
                    $translations[$lang][$filename] = $this->loadTranslationsFromFile($file->getPathname());
                }
            }
        }
        
        return view('admin.translations.strings', compact('languages', 'translations'));
    }
    
    private function loadTranslationsFromFile($filePath)
    {
        // Load the translations from the file
        $translations = require $filePath;
        
        // Check if it's a multi-level array and process accordingly
        return $this->flattenTranslations($translations);
    }
    
    private function flattenTranslations($translations, $prefix = '')
    {
        $flattened = [];
        
        foreach ($translations as $key => $value) {
            $newKey = $prefix ? $prefix.'.'.$key : $key;
            
            if (is_array($value)) {
                // Recursively flatten nested arrays
                $flattened = array_merge($flattened, $this->flattenTranslations($value, $newKey));
            } else {
                // Non-array values, store them directly
                $flattened[$newKey] = $value;
            }
        }
        
        return $flattened;
    }
    
    public function updateStrings(Request $request, $lang)
    {
        $file = $request->input('file');
        $translations = $request->input('translations');
        
        // Load the existing file to preserve its structure
        $filePath = resource_path("lang/{$lang}/{$file}.php");
        
        if (File::exists($filePath)) {
            $existingTranslations = require $filePath;
        } else {
            $existingTranslations = [];
        }
        
        // Rebuild the nested array from the flattened input
        $updatedTranslations = $this->expandTranslations($translations);
        
        // Merge the updated translations with existing ones
        $mergedTranslations = array_merge_recursive($existingTranslations, $updatedTranslations);
        
        // Export the merged translations back to the file
        $content = "<?php\n\nreturn ".var_export($mergedTranslations, true).";\n";
        File::put($filePath, $content);
        
        return redirect()->back()->with('success', __('Translations updated successfully.'));
    }
    
    private function expandTranslations(array $translations)
    {
        $expanded = [];
        
        foreach ($translations as $key => $value) {
            // Handle nested keys using dot notation
            Arr::set($expanded, $key, $value);
        }
        
        return $expanded;
    }
}
