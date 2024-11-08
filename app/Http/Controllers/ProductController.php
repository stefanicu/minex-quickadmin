<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::leftJoin('product_translations','products.id','=','product_translations.product_id')
            ->where('products.online','=',1)
            ->where('product_translations.online','=',1)
            ->where('product_translations.slug','=',$request->slug)
            ->where('product_translations.locale','=',app()->getLocale())
            ->select(sprintf('%s.*', (new Product)->table),'product_translations.name as name','product_translations.slug as slug')
            ->first();

        if($product !== null)
        {
            return view('product', compact( 'product'));
        }

        return view('products', compact( 'product'));
    }
}
