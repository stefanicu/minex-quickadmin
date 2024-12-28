<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        if ( ! $request->app_slug) {
            abort(404);
        }
        
        $currentLocale = app()->getLocale();
        
        $application_slug = $request->app_slug;
        $category_slug = $request->cat_slug;
        
        $category = Category::whereTranslation('slug', $category_slug)
            ->whereTranslation('locale', $currentLocale)
            ->first();
        
        $application = Application::whereTranslation('slug', $application_slug)
            ->with('translations')
            ->first();
        
        $products = Category::find($category->id)
            ->products()
            ->with('media')
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale)
                    ->where('online', 1);  // Ensure only translations with 'online' = 1 are included
            })
            ->orderByTranslation('name')
            ->get();
        
        $categories = Category::whereHas('products', function ($query) use ($application) {
            // Filter products that belong to the specified category
            $query->whereHas('applications', function ($query) use ($application) {
                $query->where('applications.id', $application->id);
            });
        })
            ->with('translations') // Load translations for each application
            ->orderByTranslation('name')
            ->withCount([
                'products as products_count' => function ($query) use ($currentLocale) {
                    $query->whereHas('translations', function ($query) use ($currentLocale) {
                        $query->where('locale', $currentLocale)
                            ->where('online', 1);
                    });
                }
            ])
            ->having('products_count', '>', 0) // Filter out categories with zero products
            ->get();
        
        
        if ($categories->count() === 0) {
            return redirect(url(''));
        }
        
        if ($products->count() == 1) {
            $product = $products->first();
            
            return redirect(route('product.'.app()->getLocale(),
                ['app_slug' => $application_slug, 'cat_slug' => $category_slug, 'prod_slug' => $product->slug]));
        }
        
        $app_slugs = null;
        $cat_slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $app_slugs[$locale] = $application->translate($locale)->slug ?? '';
            $cat_slugs[$locale] = $category->translate($locale)->slug ?? '';
        }
        
        return view('products', compact('category', 'categories', 'products', 'application', 'app_slugs', 'cat_slugs'));
    }
}
