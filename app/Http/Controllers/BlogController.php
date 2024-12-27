<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ( ! is_numeric($request->slug)) {
            $blog = Blog::whereTranslation('slug', $request->slug)
                ->whereTranslation('locale', app()->getLocale())
                ->first();
            
            
            if ($blog) {
                $blog = Blog::with('translations', 'media')
                    ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
                    ->select('blogs.id as id', 'name', 'slug', 'created_at')
                    ->where('blog_translations.online', '=', 1)
                    ->where('blog_translations.blog_id', '=', $blog->id)
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
                ->where('locale', '=', 'en')
                ->where('blog_translations.online', '=', 1)
                ->where('blog_translations.blog_id', '=', $request->slug)
                ->orderBy('created_at', 'desc')->first();
            
            $blog->name = trans('pages.no_translated_title');
            $blog->content = trans('pages.no_translated_message');
        }
        
        $blogs = Blog::with('translations', 'media')
            ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
            ->select('blogs.id as id', 'name', 'slug', 'created_at')
            ->where('locale', '=', app()->getLocale())
            ->where('blogs.online', '=', 1)
            ->where('blog_translations.online', '=', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $slug_blog = $blog->translate($locale)->slug ?? $blog->id;
            $slugs[$locale] = $slug_blog;
        }
        
        return view('blog', compact('blogs', 'blog', 'slugs'));
    }
}
