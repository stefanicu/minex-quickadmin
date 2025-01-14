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
            'products' => ['table' => 'products', 'translation_table' => 'product_translations', 'foreign_key' => 'product_id', 'filter' => ['online' => 1]],
            'industries' => ['table' => 'industries', 'translation_table' => 'industry_translations', 'foreign_key' => 'industry_id', 'filter' => ['online' => 1]],
            'references' => ['table' => 'references', 'translation_table' => 'reference_translations', 'foreign_key' => 'reference_id', 'filter' => ['online' => 1]],
            'testimonials' => ['table' => 'testimonials', 'translation_table' => 'testimonial_translations', 'foreign_key' => 'testimonial_id', 'filter' => ['online' => 1]],
            'blogs' => ['table' => 'blogs', 'translation_table' => 'blog_translations', 'foreign_key' => 'blog_id', 'filter' => ['online' => 1]],
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
        
        // Specify the specific files you want to include
        $specificFiles = ['form', 'pages', 'pagination', 'seo', 'slugs'];
        // $specificFiles = ['seo'];
        
        $englishPath = "{$basePath}/en";
        $englishFiles = File::exists($englishPath) ? File::allFiles($englishPath) : [];
        
        foreach ($languages as $lang) {
            $langPath = "{$basePath}/{$lang}";
            
            foreach ($englishFiles as $file) {
                $filename = $file->getFilenameWithoutExtension();
                
                // Skip files not in the specific list
                if ( ! in_array($filename, $specificFiles)) {
                    continue;
                }
                
                $englishData = File::getRequire($file->getPathname());
                
                // Ensure the language file exists
                if ( ! File::exists("{$langPath}/{$file->getFilename()}")) {
                    if ( ! File::exists($langPath)) {
                        File::makeDirectory($langPath, 0755, true);
                    }
                    
                    // Create a file with English keys and empty values
                    $emptyData = $this->emptyValues($englishData);
                    File::put("{$langPath}/{$file->getFilename()}", "<?php\n\nreturn ".var_export($emptyData, true).";\n");
                }
                
                // Load the existing language file
                $langData = File::getRequire("{$langPath}/{$file->getFilename()}");
                
                // Ensure the translations array is initialized for each language
                if ( ! isset($translations[$lang])) {
                    $translations[$lang] = [];
                }
                
                // Merge missing keys and preserve structure
                $mergedData = array_replace_recursive($this->emptyValues($englishData), $langData);
                $translations[$lang][$filename] = $this->flattenArray($mergedData);
            }
        }
        
        return view('admin.translations.strings', compact('languages', 'translations', 'specificFiles'));
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
        return $result;
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
    
    
    public function translate($lang)
    {
        $basePath = resource_path('lang');
        $englishPath = "{$basePath}/en";
        $targetPath = "{$basePath}/{$lang}";
        
        $specificFiles = ['form', 'pages', 'pagination', 'seo', 'slugs'];
        //$specificFiles = ['seo']; // Specify files to include
        $englishFiles = File::exists($englishPath) ? File::allFiles($englishPath) : [];
        
        foreach ($englishFiles as $file) {
            $filename = $file->getFilenameWithoutExtension();
            
            if ( ! in_array($filename, $specificFiles)) {
                continue; // Skip files not in the specific list
            }
            
            $englishData = File::getRequire($file->getPathname());
            $targetFilePath = "{$targetPath}/{$file->getFilename()}";
            
            $targetData = File::exists($targetFilePath)
                ? File::getRequire($targetFilePath)
                : $this->emptyValues($englishData);
            
            // Translate using the English data as reference
            $translatedData = $this->translateArray($targetData, $lang, 'en', $englishData);
            
            // Save the translated data back to the target file
            File::put($targetFilePath, "<?php\n\nreturn ".var_export($translatedData, true).";\n");
        }
        
        return redirect()->back()->with('success', "Translations for {$lang} updated successfully!");
    }
    
    /**
     * Translate an array, preserving its structure and only translating empty values.
     */
    private function translateArray(array $data, $targetLang, $sourceLang = 'en', array $englishData = [])
    {
        $translated = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Handle multi-level arrays recursively
                $translated[$key] = $this->translateArray($value, $targetLang, $sourceLang, $englishData[$key] ?? []);
            } else {
                // Use the English version of the value for translation
                $originalText = $englishData[$key] ?? '';
                $translated[$key] = ! empty($value) ? $value : ChatGPTService::translate($originalText, $targetLang, $sourceLang);
            }
        }
        
        return $translated;
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
