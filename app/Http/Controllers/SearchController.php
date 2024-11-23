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

        $products = Product::with(['translations', 'media'])
            ->whereHas('translations', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            })
            ->whereHas('translations', function ($query) {
                $query->where('locale', app()->getLocale());
            })
            ->get();

        return view('search', compact('blogs','products','search'));
    }
}
