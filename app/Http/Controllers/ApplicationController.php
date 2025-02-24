<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Traits\HasMetaData;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    use HasMetaData;
    
    public function index(Request $request)
    {
        if ( ! $request->app_slug) {
            abort(404);
        }
        $currentLocale = app()->getLocale();
        
        $application_slug = $request->app_slug;
        
        $application = Application::whereHas('translations', function ($query) use ($application_slug, $currentLocale) {
            $query->where('slug', $application_slug)
                ->where('locale', $currentLocale)
                ->where('online', 1); // Ensure the translation is marked as online
        })
            ->first();
        
        $categories = Category::where('application_id', $application->id) // Filter by application_id
        ->whereHas('translations', function ($query) {
            $query->where('online', 1);
        })
            ->orderByTranslation('name') // Order by translated name
            ->get();
        
        
        $app_slugs = null;
        foreach (config('translatable.locales') as $locale) {
            $app_slugs[$locale] = $application->translate($locale)->slug ?? $application->id;
        }
        
        $metaData = $this->getMetaData($application);
        
        return view('application',
            compact(
                'application',
                'categories',
                'app_slugs',
                'metaData'
            )
        );
    }
}
