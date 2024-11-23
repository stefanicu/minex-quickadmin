<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Brand;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brand::join('brand_translations', function ($join) {
            $join->on('brands.id', '=', 'brand_translations.brand_id')
                ->where('brand_translations.online', '=', 1)
                ->where('brand_translations.locale', '=', app()->getLocale());
            })
            ->whereHas('products')
            ->selectRaw('brands.id, brands.name, brands.slug')
            ->groupByRaw('brands.id, brands.name, brands.slug')
            ->orderBy('brands.name')
            ->get();

        return view('brands', compact('brands'));
    }
}
