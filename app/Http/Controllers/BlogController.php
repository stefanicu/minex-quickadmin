<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Traits\HasMetaData;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        $blog_slug = $request->slug;
        if ( ! is_numeric($blog_slug)) {
            $blog = Blog::with('translations', 'media')
                ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
                ->select('blogs.id as id', 'name', 'slug', 'created_at')
                ->where('blog_translations.online', '=', 1)
                ->where('blog_translations.slug', '=', $blog_slug)
                ->where('locale', '=', app()->getLocale())
                ->orderBy('created_at', 'desc')->first();
        } else {
            $blog_id = (int) $blog_slug;
            $blog = Blog::with('translations', 'media')
                ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
                ->select('blogs.id as id', 'name', 'slug', 'created_at')
                ->where('blog_translations.online', '=', 1)
                ->where('blog_translations.blog_id', '=', $blog_id)
                ->orderBy('created_at', 'desc')->first();
            
            $blog->name = trans('pages.no_translated_title');
            $blog->content = trans('pages.no_translated_message');
        }
        
        if ( ! $blog) {
            if (auth()->check()) {
                $blog = Blog::with('translations', 'media')
                    ->leftJoin('blog_translations', 'blogs.id', '=', 'blog_translations.blog_id')
                    ->select('blogs.id as id', 'name', 'slug', 'created_at')
                    ->where('blog_translations.slug', '=', $blog_slug)
                    ->where('locale', '=', app()->getLocale())
                    ->orderBy('created_at', 'desc')->first();
            } else {
                abort(404);
            }
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
        
        $metaData = $this->getMetaData($blog);
        
        return view('blog', compact('blogs', 'blog', 'slugs', 'metaData'));
    }
}
