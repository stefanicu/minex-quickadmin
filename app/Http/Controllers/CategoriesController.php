<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if ( ! $request->app_slug) {
            abort(404);
        }
        
        $currentLocale = app()->getLocale();
        
        $application_slug = $request->app_slug;
        
        $application = Application::whereTranslation('slug', $application_slug)->whereTranslation('locale',
            $currentLocale)->first();
        
        if ( ! $application) {
            $application = Application::whereTranslation('slug', $application_slug)->whereTranslation('locale',
                'en')->first();
        }
        
        $productIds = null;
        if ($application) {
            $productIds = Application::find($application->id)
                ->products()
                ->whereHas('translations', function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale);
                })
                ->pluck('products.id')
                ->toArray();
        }
        
        if ( ! $productIds) {
            $categories = Category::with('product_main_image', 'product_main_image.media')
                ->orderByTranslation('name')
                ->get();
        } else {
            $categories = Category::with('product_main_image', 'product_main_image.media')
                ->whereHas('products', function ($query) use ($productIds) {
                    $query->whereIn('products.id', $productIds);
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
            $slug_app = $application->translate($locale)->slug ?? $application->id;
            $app_slugs[$locale] = $slug_app;
        }
        
        return view('categories', compact('application', 'categories', 'app_slugs'));
    }
}
