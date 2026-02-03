<?php

namespace App\Providers;

use App\Models\Application;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set umask globally
        umask(0002); // Allow group and others to read files
        
        URL::forceScheme('https');
        
        Paginator::useBootstrapFour();
        
        if ( ! request()->is('admin/*') && ! app()->runningUnitTests()) {
            // Share the applications globally for all views
            View::composer('*', function ($view) {
                // Get all applications, or whatever specific data you need
                
                $applications = Application::whereHas('categories') // Only applications with at least one category
                ->select('applications.id')
                    ->with([
                        'translations' => function ($query) {
                            $query->where('locale', app()->getLocale());
                        }
                    ])
                    ->get();
                
                // Now, sort the results manually in PHP if needed:
                $applications = $applications->sortBy('translations.0.name');
                
                $view->with('applications', $applications);  // Share with all views
            });
        }
    }
}