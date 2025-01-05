<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Product;
use App\Traits\HasMetaData;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        if ( ! $request->app_slug) {
            abort(404);
        }
        $currentLocale = app()->getLocale();
        
        $application_slug = $request->app_slug;
        
        if ( ! is_numeric($application_slug)) {
            $application = Application::whereTranslation('slug', $application_slug)
                ->whereTranslation('locale', $currentLocale)
                ->whereHas('translations', function ($query) {
                    $query->where('online', 1); // Ensure the translation is marked as online
                })
                ->first();
        } else {
            $application_id = $application_slug;
            $application = Application::whereTranslation('application_id', $application_id)
                ->whereHas('translations', function ($query) {
                    $query->where('online', 1); // Ensure the translation is marked as online
                })
                ->first();
            
            $application->name = trans('pages.no_translated_title');
            $application->description = trans('pages.no_translated_message');
            
            $categories = [];
            
            $app_slugs = null;
            foreach (config('translatable.locales') as $locale) {
                $app_slugs[$locale] = $application->translate($locale)->slug ?? $application->id;
            }
            
            return view('application', compact('application', 'categories', 'app_slugs'));
        }
        
        $productIds = null;
        if ($application) {
            $productIds = Application::find($application->id)
                ->products()
                ->whereHas('translations', function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale)->where('online', 1);
                })
                ->pluck('products.id')
                ->toArray();
        }
        
        if ( ! $productIds) {
            // TODO: replace categories with a message
            $categories = Category::with('product_main_image', 'product_main_image.media')
                ->whereHas('translations', function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale)
                        ->where('online', 1)
                        ->whereNotNull('name')
                        ->where('name', '!=', '')
                        ->whereNotNull('slug')
                        ->where('slug', '!=', '');
                })
                ->orderByTranslation('name')
                ->limit(3)
                ->get();
        } else {
            $categories = Category::with('product_main_image.media')
                ->whereHas('products', function ($query) use ($productIds) {
                    $query->whereIn('products.id', $productIds);
                })
                ->whereHas('translations', function ($query) {
                    $query->where('online', 1);
                })
                ->orderByTranslation('name')
                ->get();
        }
        
        if ($categories->count() == 1) {
            $category = $categories->first();
            $category_id = $category->id;
            
            $products = Product::with([
                'translations' => function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale);
                }
            ])
                ->whereHas('translations', function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale);
                })
                ->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                })
                ->get();
            
            if ($products->count() === 1) {
                $product = $products->first();
                return redirect(route('product.'.app()->getLocale(),
                    ['app_slug' => $application->slug, 'cat_slug' => $category->slug, 'prod_slug' => $product->slug]));
            }
            
            return redirect(route('products.'.app()->getLocale(),
                ['app_slug' => $application->slug, 'cat_slug' => $category->slug]));
        }
        
        $app_slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $app_slugs[$locale] = $application->translate($locale)->slug ?? $application->id;
        }
        
        
        $metaData = $this->getMetaData($application);
        
        return view('application',
            compact(
                'application',
                'categories',
                'app_slugs',
                'metaData'
            )
        );
    }
}
