<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Blog;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        $blogs = Blog::leftJoin('blog_translations','blogs.id', '=', 'blog_translations.blog_id')
            //->select('blog_id','name','slug','created_at')
            ->where('locale','=',app()->getLocale())
            ->where('blog_translations.online','=',1)
            ->orderBy('created_at','desc')->get();

        return view('blog', compact('applications','blogs'));
    }
}
