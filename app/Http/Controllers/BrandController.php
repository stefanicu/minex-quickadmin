<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Traits\HasMetaData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        $currentLocale = app()->getLocale();
        
        $brand_slug = $request->slug;
        
        if ( ! is_numeric($brand_slug)) {
            $brand = Brand::select('id', 'name', 'slug')
                ->where('slug', '=', $brand_slug)
                ->first();
        } else {
            $brand = Brand::select('id', 'name', 'slug')
                ->where('id', '=', $brand_slug)
                ->first();
        }
        
        if ( ! $brand) {
            abort(404);
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
        
        $products = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('product_translations.online', '=', 1)
            ->where('brand_id', '=', $brand->id)
            ->where('product_translations.locale', '=', $currentLocale)
            ->select(sprintf('%s.*', (new Product)->table), 'product_translations.name as name',
                'product_translations.slug as slug')
            ->orderBy('name')
            ->get();
        
        if ($products->count() == 1) {
            $product = $products->first();
            return redirect()->route('product_brand.'.app()->getLocale(),
                ['brand_slug' => $brand->slug, 'prod_slug' => $product->slug]);
        }
        
        $slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $slug_brand = $brand->slug ?? $brand->id;
            $slugs[$locale] = $slug_brand;
        }
        
        $metaData = $this->getMetaData($brand);
        
        return view(
            'brand',
            compact(
                'brand', 'brands', 'products', 'slugs',
                'metaData'
            )
        );
    }
}
