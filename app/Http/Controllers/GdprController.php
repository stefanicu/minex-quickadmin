<?php

namespace App\Http\Controllers;

use App\Models\BlogTranslation;

class GdprController extends Controller
{
    public function index()
    {
        $blog = BlogTranslation::select('blog_id', 'name', 'content')
            ->where('blog_id', '=', 227)
            ->where('locale', '=', app()->getLocale())
            ->first();
        
        return view('gdpr', compact('blog'));
    }
}
