<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Page;
use App\Traits\HasMetaData;
use Illuminate\Http\Request;

// TODO: to separate controllers without redirect for: Page, Application, Brand

class AppPageDynamicController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        $page_slug = $request->slug;
        
        $currentLocale = app()->getLocale();
        
        $page = Page::with('translations', 'media')
            ->leftJoin('page_translations', 'pages.id', '=', 'page_translations.page_id')
            ->where('page_translations.slug', '=', $page_slug)
            ->where('locale', '=', $currentLocale)
            ->where('page_translations.online', '=', 1)
            ->orderBy('created_at', 'desc')
            ->select('pages.id as id', 'name', 'slug', 'created_at')
            ->first();
        
        $pages = Page::with('translations', 'media')
            ->leftJoin('page_translations', 'pages.id', '=', 'page_translations.page_id')
            ->select('pages.id as id', 'name', 'slug', 'created_at')
            ->where('locale', '=', $currentLocale)
            ->where('pages.online', '=', 1)
            ->where('page_translations.online', '=', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        # Application page if not exist the page with given slug
        if ($page === null) {
            $application_slug = $request->slug;
            
            $application = Application::whereHas('translations', function ($query) use ($application_slug, $currentLocale) {
                $query->where('slug', $application_slug)
                    ->where('locale', $currentLocale)
                    ->where('online', 1); // Ensure the translation is marked as online
            })
                ->first();
            
            //            if ($application === null) {
            //                $brand_slug = $request->slug;
            //
            //                $brand = Brand::select('id', 'name', 'slug')
            //                    ->where('slug', '=', $brand_slug)
            //                    ->first();
            //
            //                if ($brand !== null) {
            //                    // Step 1: Count products grouped by brand_id
            //                    $productCounts = DB::table('products')
            //                        ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            //                        ->selectRaw('products.brand_id, COUNT(*) as cnt')
            //                        ->where('products.deleted_at', '=', null) // Exclude soft-deleted products
            //                        ->where('product_translations.locale', '=', $currentLocale)
            //                        ->where('product_translations.online', '=', 1)
            //                        ->groupBy('products.brand_id')
            //                        ->get();
            //
            //                    // Step 2: Attach brand details to the counts
            //                    $brands = Brand::leftJoin('brand_translations', 'brands.id', '=', 'brand_translations.brand_id')
            //                        ->select('brands.id', 'brands.name', 'brands.slug')
            //                        ->where('brand_translations.online', '=', 1)
            //                        ->where('brand_translations.locale', '=', $currentLocale)
            //                        ->get()
            //                        ->map(function ($brand) use ($productCounts) {
            //                            $brand->cnt = $productCounts->firstWhere('brand_id', $brand->id)->cnt ?? 0;
            //                            return $brand;
            //                        })
            //                        ->filter(function ($brand) {
            //                            return $brand->cnt > 0; // Only keep brands with products
            //                        })
            //                        ->sortBy('name'); // Order by name
            //
            //                    $products = Product::with('media')
            //                        ->leftJoin('product_translations', function ($join) use ($currentLocale) {
            //                            $join->on('products.id', '=', 'product_translations.product_id')
            //                                ->where('product_translations.locale', $currentLocale)
            //                                ->where('product_translations.online', 1);
            //                        })
            //                        ->leftJoin('application_translations', function ($join) use ($currentLocale) {
            //                            $join->on('products.application_id', '=', 'application_translations.application_id')
            //                                ->where('application_translations.locale', $currentLocale);
            //                        })
            //                        ->leftJoin('category_translations', function ($join) use ($currentLocale) {
            //                            $join->on('products.category_id', '=', 'category_translations.category_id')
            //                                ->where('category_translations.locale', $currentLocale);
            //                        })
            //                        ->where('products.brand_id', $brand->id)
            //                        ->where('product_translations.online', 1)
            //                        ->select(
            //                            'products.*',
            //                            'product_translations.name as name',
            //                            'product_translations.slug as slug',
            //                            'application_translations.slug as application_slug',
            //                            'category_translations.slug as category_slug'
            //                        )
            //                        ->orderBy('product_translations.name')
            //                        ->get();
            //
            //                    $slugs = null;
            //                    foreach (config('translatable.locales') as $locale) {
            //                        $slug_brand = $brand->slug ?? $brand->id;
            //                        $slugs[$locale] = $slug_brand;
            //                    }
            //
            //                    $metaData = $this->getMetaData($brand);
            //
            //                    return view(
            //                        'brand',
            //                        compact(
            //                            'brand', 'brands', 'products', 'slugs',
            //                            'metaData'
            //                        )
            //                    );
            //                }
            //            }
            
            if ($application !== null) {
                $categories = Category::where('application_id', $application->id) // Filter by application_id
                ->whereHas('translations', function ($query) {
                    $query->where('online', 1);
                })
                    ->whereHas('products', function ($query) use ($currentLocale) {
                        $query->whereHas('translations', function ($query) use ($currentLocale) {
                            $query->where('locale', $currentLocale)
                                ->where('online', 1);
                        });
                    }) // Ensure the category has at least one product with an online translation
                    ->orderByTranslation('name') // Order by translated name
                    ->get();
                
                
                $slugs = null;
                foreach (config('translatable.locales') as $locale) {
                    $slugs[$locale] = $application->translate($locale)->slug ?? $application->id;
                }
                
                $metaData = $this->getMetaData($application);
                return view('application',
                    compact(
                        'application',
                        'categories',
                        'slugs',
                        'metaData'
                    )
                );
            }
        }
        
        if ( ! $page) {
            abort(404);
        }
        
        $slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $slugs[$locale] = $page->translate($locale)->slug ?? $page->id;
        }
        
        $metaData = $this->getMetaData($page);
        
        return view('page', compact('pages', 'page', 'slugs', 'metaData'));
    }
}
