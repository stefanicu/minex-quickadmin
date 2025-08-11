<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Product;
use App\Traits\HasMetaData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        if ( ! $request->app_slug) {
            abort(404);
        }
        
        $currentLocale = app()->getLocale();
        
        $application_slug = $request->app_slug;
        $category_slug = $request->cat_slug;
        
        $application = Application::whereTranslation('slug', $application_slug)
            ->with('translations')
            ->first();
        
        $category = Category::whereTranslation('slug', $category_slug, $currentLocale)
            ->with('translations')
            ->first();
        
        $products = Product::where('category_id', $category->id)
            ->with('media')
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale)
                    ->where('product_translations.online', 1);
            })
            ->orderByTranslation('name')
            ->get();
        
        $categories = Category::where('application_id', $application->id)
            ->with('translations') // Load category translations
            ->orderByTranslation('name') // Order by translated name
            ->withCount([
                'products as products_count' => function ($query) use ($currentLocale) {
                    $query->whereExists(function ($subQuery) use ($currentLocale) {
                        $subQuery->select(DB::raw(1))
                            ->from('product_translations')
                            ->whereRaw('product_translations.product_id = products.id')
                            ->where('product_translations.locale', $currentLocale)
                            ->where('product_translations.online', 1);
                    });
                }
            ])
            ->having('products_count', '>', 0) // ✅ ensures only categories with products
            ->whereExists(function ($query) use ($currentLocale) {
                // Ensures that each category has at least one product with an online translation
                $query->select(DB::raw(1))
                    ->from('products')
                    ->whereRaw('products.category_id = categories.id')
                    ->whereExists(function ($subQuery) use ($currentLocale) {
                        $subQuery->select(DB::raw(1))
                            ->from('product_translations')
                            ->whereRaw('product_translations.product_id = products.id')
                            ->where('product_translations.locale', $currentLocale)
                            ->where('product_translations.online', 1);
                    });
            })
            ->get();
        
        $app_slugs = null;
        $cat_slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $app_slugs[$locale] = $application->translate($locale)->slug ?? $application->translate('en')->slug;
            $cat_slugs[$locale] = $category->translate($locale)->slug ?? $category->translate('en')->slug;
        }
        
        $metaData = $this->getMetaData($category);
        
        return view(
            'category',
            compact(
                'category',
                'categories',
                'products',
                'application',
                'app_slugs',
                'cat_slugs',
                'metaData'
            )
        );
    }
}

// todo: optimize quick, we will start the project again soon