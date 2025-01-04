<?php

namespace App\Http\Controllers;

use App\Models\Brand;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brand::join('brand_translations', function ($join) {
            $join->on('brands.id', '=', 'brand_translations.brand_id')
                //->where('brand_translations.online', '=', 1)
                ->where('brand_translations.locale', '=', app()->getLocale());
        })
//            ->whereHas('products')
            ->selectRaw('brands.id, brands.name, brands.slug')
            ->groupByRaw('brands.id, brands.name, brands.slug')
            ->orderBy('brands.name')
            ->get();
        
        $meta_title = trans('pages.brands');
        $meta_description = trans('pages.brands');
        $canonical_url = null;
        $author = 'Minex Group International';
        $robots = null;
        $meta_image_url = null;
        $meta_image_width = null;
        $meta_image_height = null;
        $meta_image_name = null;
        $og_type = 'website';
        
        
        return view(
            'brands',
            compact(
                'brands',
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
