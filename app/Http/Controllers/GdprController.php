<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\BlogTranslation;

class GdprController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        $blog = BlogTranslation::select('blog_id','name','content')
            ->where('slug','like','%gdpr%')
            ->where('locale','=',app()->getLocale())
            ->first();

        return view('gdpr', compact('applications','blog'));
    }
}
