<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brand_slug = $request->slug;

        $brand = Brand::select('id','name','slug')
            ->where('slug','=',$brand_slug)
            ->first();

        $brands = Brand::leftJoin('brand_translations', 'brands.id', '=', 'brand_translations.brand_id')
            ->leftJoin('products','brands.id','=','products.brand_id')
            ->selectRAW('brands.id, brands.name, brands.slug, count(products.brand_id) as cnt')
            ->where('brands.online','=',1)
            ->where('brand_translations.online','=',1)
            ->where('brand_translations.locale','=',app()->getLocale())
            ->groupByRaw('brands.id, brands.name, brands.slug')
            ->orderBy('brands.name')
            ->get();

        $products = Product::leftJoin('product_translations','products.id','=','product_translations.product_id')
            ->where('products.online','=',1)
            ->where('product_translations.online','=',1)
            ->where('brand_id','=',$brand->id)
            ->where('product_translations.locale','=',app()->getLocale())
            ->select(sprintf('%s.*', (new Product)->table),'product_translations.name as name','product_translations.slug as slug')
            ->get();

        if($products->count() == 1)
        {
            $product = $products->first();
            $slug = $product->slug;
            return redirect()->route('product.index', compact('product', 'slug'));
        }

        $products = $products->all();
        return view('brand', compact('brand', 'brands', 'products'));
    }
}
