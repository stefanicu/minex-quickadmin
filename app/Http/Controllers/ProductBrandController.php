<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Productfile;
use App\Traits\HasMetaData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBrandController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        if ( ! $request->brand_slug) {
            abort(404);
        }
        
        $currentLocale = app()->getLocale();
        
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
            $product = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->with('translations', 'media')
                ->where('product_translations.online', '=', 1)
                ->where('product_translations.product_id', '=', (int) $product_slug)
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
        
        $brandOfflineDefaultMessage = trans('pages.no_brand_default_message');
        
        $brand = null;
        if (isset($product->brand_id)) {
            $brand = Brand::find($product->brand_id);
        }
        
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
        
        $brand_id = $brand->id;
        $similar_products = Product::whereHas('categories', function ($query) use ($brand_id) {
            $query->where('brand_id', $brand_id);
        })
            ->where('id', '!=', $product->id)
            ->get();
        
        $prod_slugs = null;
        $brand_slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $slug_prod = $product->translate($locale)->slug ?? $product->id;
            $prod_slugs[$locale] = $slug_prod;
            $slug_brand = $brand->slug ?? '';
            $brand_slugs[$locale] = $slug_brand;
        }
        
        // Step 1: Count products grouped by brand_id
        $productCounts = DB::table('products')
            ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->selectRaw('products.brand_id, COUNT(*) as cnt')
            ->where('products.deleted_at', '=', null) // Exclude soft-deleted products
            ->where('product_translations.locale', '=', $currentLocale)
            ->groupBy('products.brand_id')
            ->get();
        
        // Step 2: Attach brand details to the counts
        $brands = Brand::leftJoin('brand_translations', 'brands.id', '=', 'brand_translations.brand_id')
            ->select('brands.id', 'brands.name', 'brands.slug')
            ->where('brand_translations.online', '=', 1)
            ->where('brand_translations.locale', '=', $currentLocale)
            ->get()
            ->map(function ($brand) use ($productCounts) {
                $brand->cnt = $productCounts->firstWhere('brand_id', $brand->id)->cnt ?? 0;
                return $brand;
            })
            ->filter(function ($brand) {
                return $brand->cnt > 0; // Only keep brands with products
            })
            ->sortBy('name'); // Order by name
        
        $metaData = $this->getMetaData($product);
        
        return view(
            'product_brand',
            compact(
                'product',
                'references',
                'files',
                'similar_products',
                'referrer',
                'brand',
                'brands',
                'brandOfflineMessage',
                'prod_slugs',
                'brand_slugs',
                'metaData'
            )
        );
    }
}
