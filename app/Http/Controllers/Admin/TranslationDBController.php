<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TranslateWithQueue;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TranslationDBController extends Controller
{
    use TranslateWithQueue;
    
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
            'brands' => ['table' => 'brands', 'translation_table' => 'brand_translations', 'foreign_key' => 'brand_id'],
            'industries' => ['table' => 'industries', 'translation_table' => 'industry_translations', 'foreign_key' => 'industry_id'],
            'references' => ['table' => 'references', 'translation_table' => 'reference_translations', 'foreign_key' => 'reference_id'],
            'testimonials' => ['table' => 'testimonials', 'translation_table' => 'testimonial_translations', 'foreign_key' => 'testimonial_id'],
            'blogs' => ['table' => 'blogs', 'translation_table' => 'blog_translations', 'foreign_key' => 'blog_id'],
            'products' => ['table' => 'products', 'translation_table' => 'product_translations', 'foreign_key' => 'product_id'],
        ];
        
        $data = [];
        
        // Loop through models and build queries dynamically
        foreach ($models as $key => $model) {
            if (Schema::hasColumn($model['table'], 'deleted_at')) {
                $query = DB::table($model['table'])->whereNull("{$model['table']}.deleted_at")->selectRaw('COUNT(*) as count_total');
            } else {
                $query = DB::table($model['table'])->selectRaw('COUNT(*) as count_total');
            }
            
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
    
    
    public function dbtranslate(Request $request, $locale)
    {
        // Retrieve available locales from configuration
        $availableLanguages = config('translatable.locales');
        
        // Ensure the requested locale is valid
        if ( ! in_array($locale, $availableLanguages)) {
            return redirect()->back()->withErrors(['error' => __('Invalid locale.')]);
        }
        
        // Define models and their translation details
        $models = [
            'sections' => ['table' => 'front_pages', 'translation_table' => 'front_page_translations', 'foreign_key' => 'front_page_id'],
            'applications' => ['table' => 'applications', 'translation_table' => 'application_translations', 'foreign_key' => 'application_id', 'filter' => ['online' => 1]],
            'categories' => ['table' => 'categories', 'translation_table' => 'category_translations', 'foreign_key' => 'category_id', 'filter' => ['online' => 1]],
            'brands' => ['table' => 'brands', 'translation_table' => 'brand_translations', 'foreign_key' => 'brand_id'],
            'industries' => ['table' => 'industries', 'translation_table' => 'industry_translations', 'foreign_key' => 'industry_id'],
            'references' => ['table' => 'references', 'translation_table' => 'reference_translations', 'foreign_key' => 'reference_id'],
            'testimonials' => ['table' => 'testimonials', 'translation_table' => 'testimonial_translations', 'foreign_key' => 'testimonial_id'],
            'blogs' => ['table' => 'blogs', 'translation_table' => 'blog_translations', 'foreign_key' => 'blog_id'],
            'products' => ['table' => 'products', 'translation_table' => 'product_translations', 'foreign_key' => 'product_id'],
        ];
        
        // Loop over each model for translation
        // Loop through each model for translation
        foreach ($models as $model) {
            // Check if the locale is 'en' (English)
            if ($locale === 'en') {
                // If the locale is 'en', we only need Romanian records for translation
                $records = DB::table($model['translation_table'])
                    ->whereIn('locale', ['ro']) // Fetch only Romanian records for translation into English
                    ->get();
            } else {
                // Otherwise, fetch both English and the current locale records
                $records = DB::table($model['translation_table'])
                    ->whereIn('locale', ['en']) // Get both English and the current locale records
                    ->get();
            }
            
            if (env('APP_DEBUG') === false && env('APP_ENV') === 'production') {
                $limit = 1000;
            } else {
                $limit = 5;
            }
            $index = 1;
            foreach ($records as $record) {
                if ($index <= $limit) {
                    $this->translateQueueByColumns($model['translation_table'], $model['foreign_key'], $locale, $record->{$model['foreign_key']});
                }
                $index++;
            }
        }
        
        return redirect()->back()->with('success', __('Translations updated successfully for ').$locale);
    }
    
    public function dbreset(Request $request, $locale)
    {
        // Retrieve available locales from configuration
        $availableLanguages = config('translatable.locales');
        
        // Ensure the requested locale is valid
        if ( ! in_array($locale, $availableLanguages)) {
            return redirect()->back()->withErrors(['error' => __('Invalid locale.')]);
        }
        
        // Define models and their translation details
        $models = [
            'sections' => ['table' => 'front_pages', 'translation_table' => 'front_page_translations', 'foreign_key' => 'front_page_id'],
            'applications' => ['table' => 'applications', 'translation_table' => 'application_translations', 'foreign_key' => 'application_id', 'filter' => ['online' => 1]],
            'categories' => ['table' => 'categories', 'translation_table' => 'category_translations', 'foreign_key' => 'category_id', 'filter' => ['online' => 1]],
            'brands' => ['table' => 'brands', 'translation_table' => 'brand_translations', 'foreign_key' => 'brand_id'],
            'industries' => ['table' => 'industries', 'translation_table' => 'industry_translations', 'foreign_key' => 'industry_id'],
            'references' => ['table' => 'references', 'translation_table' => 'reference_translations', 'foreign_key' => 'reference_id'],
            'testimonials' => ['table' => 'testimonials', 'translation_table' => 'testimonial_translations', 'foreign_key' => 'testimonial_id'],
            'blogs' => ['table' => 'blogs', 'translation_table' => 'blog_translations', 'foreign_key' => 'blog_id'],
            'products' => ['table' => 'products', 'translation_table' => 'product_translations', 'foreign_key' => 'product_id'],
        ];
        
        // Loop over each model for translation
        // Loop through each model for translation
        foreach ($models as $model) {
            DB::table($model['translation_table'])
                ->where('locale', $locale)
                ->delete();
        }
        
        return redirect()->back()->with('success', __('All translations was deleted successfully for ').strtoupper($locale));
    }
}
