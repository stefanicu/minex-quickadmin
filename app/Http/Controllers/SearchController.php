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
        $currentLocale = app()->getLocale();
        
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
            ->whereHas('translations', function ($query) use ($currentLocale) {
                $query->where('locale', $currentLocale);
            })
            ->get();
        
        $products = Product::leftJoin('product_translations', function ($join) use ($search, $currentLocale) {
            $join->on('products.id', '=', 'product_translations.product_id')
                ->where('product_translations.locale', $currentLocale)
                ->where('product_translations.online', 1);
        })
            ->leftJoin('application_translations', function ($join) use ($currentLocale) {
                $join->on('products.application_id', '=', 'application_translations.application_id')
                    ->where('application_translations.locale', $currentLocale);
            })
            ->leftJoin('category_translations', function ($join) use ($currentLocale) {
                $join->on('products.category_id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', $currentLocale);
            })
            ->where(function ($query) use ($search) {
                $query->where('product_translations.name', 'like', "%$search%")
                    ->orWhere('product_translations.description', 'like', "%$search%");
            })
            ->select(
                'products.*',
                'product_translations.name as name',
                'product_translations.slug as slug',
                'application_translations.slug as application_slug',
                'category_translations.slug as category_slug'
            )
            ->orderBy('product_translations.name')
            ->get();
        
        return view('search', compact('blogs', 'products', 'search'));
    }
}
