<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
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
        
        $brands = Brand::leftJoin('brand_translations', 'brands.id', '=', 'brand_translations.brand_id')
            ->leftJoin('products', 'brands.id', '=', 'products.brand_id')
            ->leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->selectRaw('brands.id, brands.name, brands.slug, COUNT(products.id) as cnt')
            //->where('brands.online', '=', 1)
            //->where('brand_translations.online', '=', 1)
            ->where('brand_translations.locale', '=', $currentLocale)
            ->where('product_translations.locale', '=', $currentLocale)
            ->groupByRaw('brands.id, brands.name, brands.slug')
            ->having('cnt', '>', 0) // Exclude brands with no products
            ->orderBy('brands.name')
            ->get();
        
        $products = Product::leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('products.online', '=', 1)
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
        
        $meta_title = $brand->meta_title ?? $brand->name;
        $meta_description = $brand->meta_description ?? $brand->name;
        $canonical_url = $brand->canonical_url ?? null;
        $author = $brand->author ?? 'Minex Group International';
        $robots = $brand->robots ?? null;
        $meta_image_url = null;
        $meta_image_width = null;
        $meta_image_height = null;
        $meta_image_name = null;
        if ($brand->getPhotoAttribute() !== null) {
            $meta_image_name = $brand->getPhotoAttribute()->getUrl();
        }
        
        $og_type = 'website';
        
        return view(
            'brand',
            compact(
                'brand', 'brands', 'products', 'slugs',
                'meta_title',
                'meta_description',
                'author',
                'robots',
                'canonical_url',
                'meta_image_name',
                'og_type',
            )
        );
    }
}
