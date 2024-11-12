<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brand::leftJoin('brand_translations', 'brands.id', '=', 'brand_translations.brand_id')
            ->leftJoin('products','brands.id','=','products.brand_id')
            ->selectRAW('brands.id, brands.name, brands.slug, count(products.brand_id) as cnt_produse')
            ->where('brands.online','=',1)
            ->where('brand_translations.online','=',1)
            ->groupByRaw('brands.id, brands.name, brands.slug')
            ->orderBy('brands.name')
            ->get();


        return view('brands', compact('brands'));
    }
}
