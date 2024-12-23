<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationTranslation;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Product;
use App\Models\Productfile;
use App\Models\ProductTranslation;
use Illuminate\Http\Request;

class ProductController__bkp extends Controller
{
    public function index(Request $request)
    {
        $productTranslation = ProductTranslation::where('slug', $request->prod_slug)->first();
        
        $application_slug = $request->app_slug;
        $category_slug = $request->cat_slug;
        $product_slug = $request->prod_slug;
        
        
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
            $product = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->with('translations', 'media')
                ->where('product_translations.slug', '=', $product_slug)
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
            
            $prod_slugs = null;
            if ($product) {
                # add variable for language change
                $prod_slugs_query = ProductTranslation::where('product_id', $product->id)->select('locale',
                    'slug')->get();
                $prod_slugs = $prod_slugs_query->keyBy('locale')->map(function ($item) {
                    return $item->slug;
                });
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
        
        if ( ! str_contains($referrer, 'search?search') &&
            ! str_contains($referrer, 'partner') &&
            ! str_contains($referrer, 'parteneri') &&
            $referrer !== null) {
            $application = Application::where('id', $application_slug)->with('translations')->first();
        } else {
            $application = Product::find($product->id)
                ->applications() // Access the many-to-many relationship
                ->with('translations') // Eager load translations for each application
                ->first();
        }
        
        if ( ! str_contains($referrer, 'search?search') &&
            ! str_contains($referrer, 'partner') &&
            ! str_contains($referrer, 'parteneri') &&
            $referrer !== null) {
            $category = Category::where('id', $category_slug)->with('translations')->first();
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
        
        if ($request->cat_slug) {
            $categoryId = $request->cat_slug;
            
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
        
        $prod_slugs = null;
        if ($product) {
            # add variable for language change
            $prod_slugs_query = ProductTranslation::where('product_id', $product->id)->select('locale',
                'slug')->get();
            $prod_slugs = $prod_slugs_query->keyBy('locale')->map(function ($item) {
                return $item->slug;
            });
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
            )
        );
    }
}
