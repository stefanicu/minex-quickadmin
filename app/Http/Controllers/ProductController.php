<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productfile;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $currentLocale = app()->getLocale();
        
        $application_slug = $request->app_slug;
        $application = Application::whereHas('translations', function ($query) use ($application_slug) {
            $query->where('slug', $application_slug);
        })->first();
        
        $category_slug = $request->cat_slug;
        $category = Category::whereHas('translations', function ($query) use ($category_slug) {
            $query->where('slug', $category_slug);
        })->first();
        
        $product_slug = $request->prod_slug;
        
        if ( ! is_numeric($product_slug)) {
            $product = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->with('translations', 'media')
                ->where('product_translations.online', '=', 1)
                ->where('product_translations.slug', '=', $product_slug)
                ->where('product_translations.locale', '=', $currentLocale)
                ->select(sprintf('%s.*', (new Product)->table))
                ->first();
        } else {
            $product_id = (int) $product_slug;
            $product = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->with('translations', 'media')
                ->where('product_translations.online', '=', 1)
                ->where('products.id', '=', $product_id)
                ->select(sprintf('%s.*', (new Product)->table))
                ->first();
            
            $product->name = trans('pages.no_translated_title');
            $product->description = trans('pages.no_translated_message');
        }
        
        if ( ! $product) {
            $product = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->with('translations', 'media')
                ->where('product_translations.slug', '=', $product_slug)
                ->select(sprintf('%s.*', (new Product)->table))
                ->first();
            
            if ($product) {
                $product->name = trans('pages.no_translated_title');
                $product->description = trans('pages.no_translated_message');
            } else {
                abort(404);
            }
        }
        
        $brandOfflineMessage = trans('pages.no_brand_default_message');
        
        $brand = null;
        if (isset($product->brand_id)) {
            $brand = Brand::find($product->brand_id);
        }
        
        if ($brand) {
                $brand->offline_message ?? $brandOfflineMessage = $brand->offline_message;
        }
        
        $references = $product->references()
            ->with([
                'translations' => function ($query) use ($currentLocale) {
                    $query->where('locale', $currentLocale);
                }
            ])
            ->get();
        
        $files = Productfile::where('product_id', $product->id)
            ->whereRaw("FIND_IN_SET(?, languages)", [$currentLocale])
            ->get();
        
        $referrer = $request->headers->get('referer');
        
        if ($application) {
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
                            $query->where('locale', $currentLocale);
                        });
                    }
                ])
                ->having('products_count', '>', 0) // Filter out categories with zero products
                ->get();
        } else {
            $application = null;
            $category = null;
            $categories = null;
        }
        
        $category_id = $category->id;
        $similar_products = Product::select('products.*', 'product_translations.name')
            ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('product_translations.online', 1)
            ->where('product_translations.locale', app()->getLocale())
            ->whereHas('categories', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })
            ->where('products.id', '!=', $product->id)
            ->whereNotNull('products.brand_id')
            ->get();
        
        $app_slugs = null;
        $cat_slugs = null;
        $prod_slugs = null;
        $brand_slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $app_slugs[$locale] = $application->translate($locale)->slug ?? '';
            $cat_slugs[$locale] = $category->translate($locale)->slug ?? '';
            $prod_slugs[$locale] = $product->translate($locale)->slug ?? $product->id;
            if (isset($brand->slug)) {
                $brand_slugs[$locale] = $brand->slug ?? '';
            }
        }
        
        return view(
            'product',
            compact(
                'product',
                'references',
                'files',
                'similar_products',
                'application',
                'category',
                'categories',
                'referrer',
                'brand',
                'brandOfflineMessage',
                'app_slugs',
                'cat_slugs',
                'prod_slugs',
                'brand_slugs',
            )
        );
    }
}
