<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Page;

class BrandsController extends Controller
{
    public function index()
    {
        $currentLocale = app()->getLocale();
        $brands = Brand::with('media')
            ->join('brand_translations', function ($join) use ($currentLocale) {
                $join->on('brands.id', '=', 'brand_translations.brand_id')
                    //->where('brand_translations.online', '=', 1)
                    ->where('brand_translations.locale', '=', $currentLocale);
            })
            //            ->whereHas('products')
            ->selectRaw('brands.id, brands.name, brands.slug')
            ->groupByRaw('brands.id, brands.name, brands.slug')
            ->orderBy('brands.name')
            ->get();
        
        $page = Page::with('translations', 'media')
            ->leftJoin('page_translations', 'pages.id', '=', 'page_translations.page_id')
            ->where('page_translations.slug', '=', 'partners')
            ->where('locale', '=', $currentLocale)
            ->where('page_translations.online', '=', 1)
            ->orderBy('created_at', 'desc')
            ->first();
        
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
        
        $metaData = getStaticMetaData([
            'meta_title' => trans('seo.brands_title'),
            'meta_description' => trans('seo.brands_description').' description',
        ]);
        
        
        return view(
            'brands',
            compact(
                'brands',
                'page',
                'metaData'
            )
        );
    }
}
