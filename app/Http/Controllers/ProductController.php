<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productfile;
use App\Models\ProductTranslation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $productTranslation = ProductTranslation::where('slug', $request->slug)->first();
        
        $currentLocale = app()->getLocale();
        
        $product = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->with('translations', 'media')
            ->where('product_translations.online', '=', 1)
            ->where('product_translations.product_id', '=', $productTranslation->product_id)
            ->where('product_translations.locale', '=', $currentLocale)
            ->select(sprintf('%s.*', (new Product)->table))
            ->first();
        
        $brandOfflineDefaultMessage = trans('pages.no_brand_default_message');
        
        
        //dd($productTranslation->product_id, $currentLocale, $product, $request->slug);
        
        if ($product === null) {
            $product = Product::with('translations', 'media')
                ->leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->where('product_translations.slug', '=', $request->slug)
                ->select(sprintf('%s.*', (new Product)->table))
                ->first();
            $product->name = trans('pages.no_translated_title');
            $product->description = trans('pages.no_translated_message');
            $brand = null;
            $references = null;
            $files = [];
            $similar_products = [];
            $application = null;
            $category = null;
            $categories = [];
            $referrer = $request->headers->get('referer');
            
            $brandOfflineMessage = $brandOfflineDefaultMessage;
            
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
                    'brandOfflineMessage'
                )
            );
        }
        
        $brand = Brand::find($product->brand_id);
        $brand->offline_message ? $brandOfflineMessage = $brand->offline_message : $brandOfflineMessage = $brandOfflineDefaultMessage;
        
        
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
        
        if (session()->has('application_id') &&
            ! str_contains($referrer, 'search?search') &&
            ! str_contains($referrer, 'partner') &&
            ! str_contains($referrer, 'parteneri') &&
            $referrer !== null) {
            $application = Application::where('id', session('application_id'))->with('translations')->first();
        } else {
            $application = Product::find($product->id)
                ->applications() // Access the many-to-many relationship
                ->with('translations') // Eager load translations for each application
                ->first();
        }
        
        if (session()->has('category_id') &&
            ! str_contains($referrer, 'search?search') &&
            ! str_contains($referrer, 'partner') &&
            ! str_contains($referrer, 'parteneri') &&
            $referrer !== null) {
            $category = Category::where('id', session('category_id'))->with('translations')->first();
        } else {
            $category = Product::find($product->id)
                ->categories() // Access the many-to-many relationship
                ->with('translations') // Eager load translations for each application
                ->first();
        }
        
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
        
        if (session()->has('category_id')) {
            $categoryId = session('category_id');
            
            $hasCategory = $product->categories()->where('categories.id', $categoryId)->exists();
            
            if ($hasCategory) {
                $similar_products = Product::whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                })
                    ->where('id', '!=', $product->id)
                    ->get();
            } else {
                $brandId = $product->brand_id;
                $similar_products = Product::whereHas('brand', function ($query) use ($brandId) {
                    $query->where('brand_id', $brandId);
                })
                    ->where('id', '!=', $product->id)
                    ->get();
                
                $application = null;
                $category = null;
                $categories = null;
            }
        } else {
            $brandId = $product->brand_id;
            $similar_products = Product::whereHas('brand', function ($query) use ($brandId) {
                $query->where('brand_id', $brandId);
            })
                ->where('id', '!=', $product->id)
                ->get();
        }

//        $currentLocale = app()->getLocale();
//
//        $categories = Category::whereHas('products', function ($query) use ($application, $currentLocale) {
//            // Filter products that belong to the specified application and exist in the current language
//            $query->whereHas('applications', function ($query) use ($application) {
//                $query->where('applications.id', $application->id);
//            })
//                ->whereHas('translations', function ($query) use ($currentLocale) {
//                    $query->where('locale', $currentLocale); // Only include products with translations in the current locale
//                });
//        })
//            ->with(['translations' => function ($query) use ($currentLocale) {
//                $query->where('locale', $currentLocale); // Load translations for each category in the current locale
//            }])
//            ->orderByTranslation('name')
//            ->withCount('products') // Adds a `products_count` attribute to each category
//            ->get();


//        $productIds = Application::find($application->id)
//            ->products()
//            ->whereHas('translations', function ($query) use ($currentLocale) {
//                $query->where('locale', $currentLocale);
//            })
//            ->pluck('id')
//            ->toArray();
//
//        $categories = Category::whereHas('products', function ($query) use ($productIds, $currentLocale) {
//            $query->whereIn('products.id', $productIds);
//        })
//            ->with('product_main_image')
//            ->orderByTranslation('name')
//            ->withCount('products') // Adds a `products_count` attribute to each category
//            ->get();
        
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
                'brandOfflineMessage'
            )
        );
    }
}
