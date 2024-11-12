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
        $blogs = Blog::with('translations','media')
            ->whereTranslationLike('name', "%$search%")  // Search in translated 'name'
            ->whereTranslationLike('content', "%$search%")  // Search in translated 'description'
            ->get();

        $products = Product::with(['translations', 'media'])
            ->whereTranslationLike('name', "%$search%")  // Search in translated 'name'
            ->whereTranslationLike('description', "%$search%")  // Search in translated 'description'
            ->get();

        return view('search', compact('blogs','products','search'));
    }
}
