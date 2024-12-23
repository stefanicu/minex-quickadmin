<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
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
        
        $category = Category::whereTranslation('slug', $category_slug)->whereTranslation('locale',
            $currentLocale)->first();
        
        
        $application = Application::whereHas('products', function ($query) use ($category) {
            // Filter products that belong to the specified category
            $query->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            });
        })
            ->with('translations') // Load translations for each application
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
        
        
        $productIds = Application::find($application->id)
            ->products()
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale);
            })
            ->pluck('id')
            ->toArray();
        
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
        
        $app_slugs = null;
        if ($application) {
            # add variable for language change
            $app_slugs_query = ApplicationTranslation::where('application_id', $application->id)->select('locale',
                'slug')->get();
            $app_slugs = $app_slugs_query->keyBy('locale')->map(function ($item) {
                return $item->slug;
            });
        }
        
        $cat_slugs = null;
        if ($category) {
            # add variable for language change
            $cat_slugs_query = CategoryTranslation::where('category_id', $category->id)->select('locale',
                'slug')->get();
            $cat_slugs = $cat_slugs_query->keyBy('locale')->map(function ($item) {
                return $item->slug;
            });
        }
        
        if ($categories->count() === 0) {
            return redirect(url(''));
        }
        
        if ($products->count() == 1) {
            $product = $products->first();
            
            return redirect(route('product.'.app()->getLocale(),
                ['app_slug' => $application_slug, 'cat_slug' => $category_slug, 'prod_slug' => $product->slug]));
        }
        
        return view('products', compact('category', 'categories', 'products', 'application', 'app_slugs', 'cat_slugs'));
    }
}
