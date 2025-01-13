<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChatGPTService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
    
    public function strings()
    {
        $languages = config('translatable.locales'); // Example: ['en', 'ro', 'bg']
        $basePath = resource_path('lang');
        $translations = [];
        
        $englishPath = "{$basePath}/en";
        $englishFiles = File::exists($englishPath) ? File::allFiles($englishPath) : [];
        
        foreach ($languages as $lang) {
            if ($lang === 'en') {
                continue;
            } // Skip English since it's the base language
            
            $langPath = "{$basePath}/{$lang}";
            
            foreach ($englishFiles as $file) {
                $filename = $file->getFilenameWithoutExtension();
                $englishData = File::getRequire($file->getPathname());
                
                if ( ! File::exists("{$langPath}/{$file->getFilename()}")) {
                    if ( ! File::exists($langPath)) {
                        File::makeDirectory($langPath, 0755, true);
                    }
                    // Create a new file with empty values based on English keys
                    $emptyData = $this->emptyValues($englishData);
                    File::put("{$langPath}/{$file->getFilename()}", "<?php\n\nreturn ".var_export($emptyData, true).";\n");
                }
                
                // Merge missing keys into the existing file
                $langData = File::getRequire("{$langPath}/{$file->getFilename()}");
                $mergedData = array_replace_recursive($this->emptyValues($englishData), $langData);
                File::put("{$langPath}/{$file->getFilename()}", "<?php\n\nreturn ".var_export($mergedData, true).";\n");
                
                $translations[$lang][$filename] = $mergedData;
            }
        }
        
        return view('admin.translations.strings', compact('languages', 'translations'));
    }
    
    /**
     * Converts all values in an array to empty strings, keeping the keys intact.
     */
    private function emptyValues(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = is_array($value) ? $this->emptyValues($value) : '';
        }
        return $result;
    }
    
    public function translate($locale)
    {
        $basePath = resource_path('lang');
        $englishPath = "{$basePath}/en";
        $langPath = "{$basePath}/{$locale}";
        
        if ( ! File::exists($englishPath) || ! File::exists($langPath)) {
            return back()->with('error', "English or {$locale} translations folder is missing.");
        }
        
        $englishFiles = File::allFiles($englishPath);
        
        foreach ($englishFiles as $file) {
            $filename = $file->getFilename();
            $englishData = File::getRequire($file->getPathname());
            $langData = File::exists("{$langPath}/{$filename}")
                ? File::getRequire("{$langPath}/{$filename}")
                : $this->emptyValues($englishData);
            
            // Translate empty values
            $translatedData = $this->translateRecursive($englishData, $langData, $locale);
            
            // Save the translated file
            File::put("{$langPath}/{$filename}", "<?php\n\nreturn ".var_export($translatedData, true).";\n");
        }
        
        return back()->with('success', "Translations for {$locale} completed.");
    }
    
    /**
     * Recursively translates empty values in an array.
     */
    private function translateRecursive(array $englishData, array $langData, $locale)
    {
        foreach ($englishData as $key => $value) {
            if (is_array($value)) {
                $langData[$key] = $this->translateRecursive($value, $langData[$key] ?? [], $locale);
            } else {
                if (empty($langData[$key])) {
                    $langData[$key] = ChatGPTService::translate($value, $locale);
                }
            }
        }
        return $langData;
    }
    
    
    public function updateStrings(Request $request, $lang)
    {
        $basePath = resource_path('lang');
        $file = $request->input('file');
        $translations = $request->input('translations', []);
        
        // Retrieve the original data from the file
        $filePath = "{$basePath}/{$lang}/{$file}.php";
        $originalData = File::exists($filePath) ? File::getRequire($filePath) : [];
        
        // Rebuild the array structure from dot notation
        $updatedData = $this->rebuildArray($translations, $originalData);
        
        // Save the updated translations back to the file
        File::put($filePath, "<?php\n\nreturn ".var_export($updatedData, true).";\n");
        
        // Wait for the file to be written
        sleep(3); // Wait for 1 second
        
        return redirect()->back()->with('success', 'Translations updated successfully!');
    }
    
    /**
     * Rebuild a multidimensional array from a dot notation array.
     */
    private function rebuildArray(array $flatArray, array $originalData = [])
    {
        $rebuilt = $originalData;
        foreach ($flatArray as $key => $value) {
            Arr::set($rebuilt, $key, $value);
        }
        return $rebuilt;
    }
}
