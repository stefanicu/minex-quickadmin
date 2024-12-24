<?php

namespace App\Http\Controllers;


use App\Models\Blog;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');


//        $blogs = Blog::with('translations','media')
//            ->whereTranslationLike('name', "%$search%")
//            ->orWhereTranslationLike('content', "%$search%")
//            ->get();

//        $products = Product::with(['translations', 'media'])
//            ->whereTranslationLike('name', "%$search%")
//            ->orWhereTranslationLike('description', "%$search%")
//            ->get();
        
        $blogs = Blog::with(['translations', 'media'])
            ->whereHas('translations', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('content', 'like', "%$search%");
            })
            ->whereHas('translations', function ($query) {
                $query->where('locale', app()->getLocale());
            })
            ->get();
        
        $products = Product::with(['translations', 'media', 'brand'])
            ->leftJoin('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->where('product_translations.locale', app()->getLocale()) // Filter by locale
            ->where(function ($query) use ($search) {
                $query->where('product_translations.name', 'like', "%$search%")
                    ->orWhere('product_translations.description', 'like', "%$search%");
            })
            ->select([
                'products.*',
                'brands.name as brand_name',
                'brands.slug as brand_slug',
                'product_translations.name as translation_name',
                'product_translations.description as translation_description',
            ])
            ->get();
        
        return view('search', compact('blogs', 'products', 'search'));
    }
}
