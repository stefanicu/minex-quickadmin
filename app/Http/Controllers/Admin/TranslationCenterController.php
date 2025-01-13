<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    
    
    public function strings(Request $request)
    {
        $languages = config('translatable.locales'); // Example: ['en', 'ro', 'bg']
        $basePath = resource_path('lang');
        $translations = [];
        
        $englishPath = "{$basePath}/en";
        $englishFiles = File::exists($englishPath) ? File::allFiles($englishPath) : [];
        
        foreach ($languages as $lang) {
            $langPath = "{$basePath}/{$lang}";
            
            foreach ($englishFiles as $file) {
                $filename = $file->getFilenameWithoutExtension();
                $englishData = File::getRequire($file->getPathname());
                
                // Ensure the language file exists; create it if missing
                if ( ! File::exists("{$langPath}/{$file->getFilename()}")) {
                    if ( ! File::exists($langPath)) {
                        File::makeDirectory($langPath, 0755, true);
                    }
                    
                    // Write an empty structure with keys from English and empty values
                    $emptyData = $this->emptyValues($englishData);
                    File::put("{$langPath}/{$file->getFilename()}", "<?php\n\nreturn ".var_export($emptyData, true).";\n");
                }
                
                // Load the existing language file
                $langData = File::getRequire("{$langPath}/{$file->getFilename()}");
                
                // Merge missing keys from English, keeping values empty for missing keys
                $mergedData = array_replace_recursive($this->emptyValues($englishData), $langData);
                $translations[$lang][$filename] = $this->flattenArray($mergedData);
            }
        }
        
        return view('admin.translations.strings', compact('languages', 'translations'));
    }
    
    /**
     * Recursively convert all values in an array to empty strings, keeping the keys intact.
     */
    private function emptyValues(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = is_array($value) ? $this->emptyValues($value) : '';
        }
        return $array;//$result;
    }
    
    /**
     * Flatten a multidimensional array into a dot notation array.
     */
    private function flattenArray(array $array, $prefix = '')
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : "{$prefix}.{$key}";
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
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
