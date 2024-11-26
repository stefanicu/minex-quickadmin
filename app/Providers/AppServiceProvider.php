<?php

namespace App\Providers;

use App\Models\Application;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrapFour();

        if ( ! request()->is('admin/*')) {

            // Share the applications globally for all views
            View::composer('*', function ($view) {
                // Get all applications, or whatever specific data you need

                $applications = Application::select(
                    'applications.id',
                    'application_translations.name',
                    'application_translations.slug'
                )
                    ->leftJoin(
                        'application_translations',
                        'application_translations.application_id',
                        '=',
                        'applications.id'
                    )
                    ->leftJoin('application_product', 'application_product.application_id', '=', 'applications.id')
                    ->leftJoin('products', 'products.id', '=', 'application_product.product_id')
                    ->leftJoin('product_translations', 'product_translations.product_id', '=', 'products.id')
                    ->where('product_translations.locale', app()->getLocale()) // Current locale is set
                    ->where('application_translations.locale', app()->getLocale()) // Current locale is set
                    ->groupBy('applications.id', 'application_translations.name', 'application_translations.slug')
                    ->orderBy('application_translations.name')
                    ->get();

                $view->with('applications', $applications);  // Share with all views
            });

        }
    }
}
