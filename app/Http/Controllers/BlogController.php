<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->slug) {
            $blog_id = Blog::whereTranslation('slug', $request->slug)->first()->id;
            
            $blog = Blog::whereTranslation('locale', app()->getLocale())->whereTranslation('blog_id',
                $blog_id)->first();
            
            //dd($blog);
            
            if ($blog) {
                $blog = Blog::with('translations', 'media')
                    ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
                    ->select('blogs.id as id', 'name', 'slug', 'created_at')
                    ->where('blog_translations.online', '=', 1)
                    ->where('blog_translations.blog_id', '=', $blog_id)
                    ->where('locale', '=', app()->getLocale())
                    ->orderBy('created_at', 'desc')->first();
            } else {
                $blog = Blog::with('translations', 'media')
                    ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
                    ->select('blogs.id as id', 'name', 'slug', 'created_at')
                    ->where('locale', '=', app()->getLocale())
                    ->where('blog_translations.online', '=', 1)
                    ->orderBy('created_at', 'desc')->first();
            }
        } else {
            $blog = Blog::with('translations', 'media')
                ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
                ->select('blogs.id as id', 'name', 'slug', 'created_at')
                ->where('locale', '=', app()->getLocale())
                ->where('blog_translations.online', '=', 1)
                ->orderBy('created_at', 'desc')->first();
        }
        
        if ($request->more) {
            $more = 0;
            $limit = 9999;
        } else {
            $more = 1;
            $limit = 10;
        }
        
        $blogs10 = Blog::with('translations', 'media')
            ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
            ->select('blogs.id as id', 'name', 'slug', 'created_at')
            ->where('locale', '=', app()->getLocale())
            ->where('blogs.online', '=', 1)
            ->where('blog_translations.online', '=', 1)
            //->where('blogs.id','!=',$blog->id)
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10);
        
        $blogs = Blog::with('translations', 'media')
            ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
            ->select('blogs.id as id', 'name', 'slug', 'created_at')
            ->where('locale', '=', app()->getLocale())
            ->where('blogs.online', '=', 1)
            ->where('blog_translations.online', '=', 1)
            //->where('blogs.id','!=',$blog->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('blog', compact('blogs10', 'blogs', 'blog', 'more'));
    }
}
