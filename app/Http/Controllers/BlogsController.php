<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::with('translations', 'media')
            ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
            ->select('blogs.id as id', 'name', 'slug', 'created_at')
            ->where('locale', '=', app()->getLocale())
            ->where('blog_translations.online', '=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $metaData = getStaticMetaData([
            'meta_title' => trans('seo.blogs_title'),
            'meta_description' => trans('seo.blogs_description'),
        ]);
        
        return view('blogs', compact('blogs', 'metaData'));
    }
}
